<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LineloginChannel;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /** line アクセストークンを発行するエンドポイント */
    private string $line_api_url = 'https://api.line.me/oauth2/v2.1/token';

    /**
     * ソーシャルログインをするページへリダイレクトする.
     */
    public function redirectToProvider(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        $socialite = $this->redirectToProviderExecute($request->provider);

        return $socialite->redirect();
    }

    /**
     * コールバック処理.
     */
    public function handleProviderCallback(Request $request): Application|RedirectResponse|Redirector
    {
        if (!isset($_COOKIE['line_login_state'])) {
            // クッキーに自生成したstateが無い場合
            session()->flash('messages.danger', 'LINEログインに失敗しました');

            return redirect(route('login'));
        }

        $state = $_COOKIE['line_login_state'];
        $callback_state = $request->query->get('state');
        if ($state !== $callback_state) {
            // 自生成したstateとcallbackのstateが一致しない場合
            session()->flash('messages.danger', 'LINEログインに失敗しました');

            return redirect(route('login'));
        }

        if (isset($_COOKIE['line_code_verifier'])) {
            // PKCE対応している場合 ※現状はPKCE未対応
            // アクセストークンを発行
            // $data = $this->createRequestData($request->query->get('code'));
            // $response = $this->createPendingRequestInstance()->post($this->line_api_url, $data);
            // $content = $response->body();

            // HTTPクライアントだとLINE側のAPI叩くことができなかったが、curlだとLINE側のAPIを叩くことができた
            // $headers = ['Content-Type: application/x-www-form-urlencoded'];
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_URL, $this->line_api_url);
            // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            // $res = curl_exec($curl);
            // curl_close($curl);
            // $json = json_decode($res);
        }

        // 成功時の処理を以後に記載する
        $social_user = Socialite::driver($request->provider)->stateless()->user();

        if (is_null($social_user->getEmail())) {
            // LINEアプリにemailを登録していないLINEユーザも居る
            session()->flash('messages.danger', 'LINEログインに失敗しました。LINEアプリにemailが未登録です。');

            return redirect(route('login'));
        }

        $user = User::query()->firstOrCreate([
            'email' => $social_user->getEmail(),
        ], [
            'email' => $social_user->getEmail(),
            'name' => $social_user->getName(),
            'password' => Hash::make(Str::random()),
            // ひとまずチーム1に固定所属
            'current_team_id' => 1,
        ]);
        // 一旦決め打ち
        $line_login_channel = LineloginChannel::query()->first();
        $line_login_channel->users()->attach([
            $user->id => $social_user->accessTokenResponseBody,
        ]);
        // LINEログインのチャネルにリンクされているLINE公式アカウントと、ユーザーの友だち関係を取得できます。
        // LINE公式アカウントを友だち追加するオプションを含む同意画面がユーザーに表示されなかった場合は、
        // friendship_status_changedクエリパラメータは含まれません。
        auth()->login($user);

        return redirect()->intended();
    }

    // ////////////////////////////////////////////// private method ////////////////////////////////////////////////
    // ////////////////////////////////////////////// private method ////////////////////////////////////////////////
    // ///////////////////////////////////////////////// private method ////////////////////////////////////////////////
    /**
     * with()は1回までに収めておく.
     */
    private function redirectToProviderExecute(string $provider): Provider
    {
        // lineソーシャルログイン時に友達追加を行えるようにする
        $bot_prompt = 'normal';
        // リプレイスアタック防止用
        $nonce = Str::random(32);
        // stateは明示的に生成する。理由はlineからのコールバック後にstateの検証をしたい為。
        $state = Str::random(32);

        $param_data = [
            'bot_prompt' => $bot_prompt,
            'nonce' => $nonce,
            'state' => $state,
        ];

        // PKCE対応
        // $code_verifier = $this->generateCodeVerifier();
        // $code_challenge = $this->generateCodeChallenge($code_verifier);
        // $code_challenge_method = 'S256';
        // $param_data['code_challenge'] = $code_challenge;
        // $param_data['code_challenge_method'] = $code_challenge_method;

        if (empty($_SERVER['HTTPS'])) {
            // 30分間有効なクッキー
            // ngrok環境では、第6引数(secure属性)をTRUEに設定するとおかしくなる時がある、、、
            setcookie('line_login_state', $state, time() + 1800, '/', '', false, true);
            // code_verifierをクッキー保存する設計は精査する必要有
            // setcookie('line_code_verifier', $code_verifier, time() + 1800, '/', '', false, true);
        }

        return Socialite::driver($provider)->with($param_data);
    }

    /**
     * code_verifier生成
     * 参考:https://qiita.com/sugamaan/items/50699432a65ad9e5829e.
     *
     * Laravel socialiteにもpkce対応関連の機能はあるみたいだが、現状はまだ公式ドキュメントにも
     * 記載が無く、どのように使えば良いか分からないので自作する
     *
     * @return string code_verifier
     */
    private function generateCodeVerifier(): string
    {
        $randomBytesString = openssl_random_pseudo_bytes(32);
        $encodedRandomString = base64_encode($randomBytesString);
        $urlSafeEncoding = [
            '=' => '',
            '+' => '-',
            '/' => '_',
        ];

        return strtr($encodedRandomString, $urlSafeEncoding);
    }

    /**
     * code_challenge生成
     * https://qiita.com/sugamaan/items/50699432a65ad9e5829e.
     *
     * Laravel socialiteにもpkce対応関連の機能はあるみたいだが、現状はまだ公式ドキュメントにも
     * 記載が無く、どのように使えば良いか分からないので自作する
     *
     * @return string code_verifier
     */
    private function generateCodeChallenge(string $code_verifier): string
    {
        $hash = hash('sha256', $code_verifier, true);

        return str_replace('=', '', strtr(base64_encode($hash), '+/', '-_'));
    }

    /**
     * リクエストデータ作成.
     */
    private function createRequestData(string $code): array
    {
        $redirect_uri = config('services.line.redirect');
        $client_id = config('services.line.client_id');
        $client_secret = config('services.line.client_secret');

        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code_verifier' => $_COOKIE['line_code_verifier'],
        ];
    }

    /**
     * PendingRequestクラスを作成.
     */
    private function createPendingRequestInstance(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);
    }

    /**
     * @throws \JsonException
     */
    private function toArray(string $json): array
    {
        try {
            $content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            echo '例外処理記載';

            throw $e;
        }

        return $content;
    }
}
