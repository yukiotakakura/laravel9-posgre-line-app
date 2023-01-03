<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JsonException;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /** line アクセストークンを発行するエンドポイント */
    private string $line_api_url = 'https://api.line.me/oauth2/v2.1/token';

    /**
     * ソーシャルログインをするページへリダイレクトする
     *
     * @param Request $request
     * @return void
     */
    public function redirectToProvider(Request $request)
    {
        $socialite = $this->redirectToProviderExcecute($request->provider);
        return $socialite->redirect();
    }

    /**
     * コールバック処理
     *
     * @param Request $request
     * @return void
     */
    public function handleProviderCallback(Request $request)
    {
        if (!isset($_COOKIE['line_login_state'])) {
            // クッキーが存在しない場合(シークレットモードの場合、htpps通信じゃない場合、第3者による攻撃の場合など、、)
            return redirect(route("login"))->with("messages.danger", "ログインに失敗しました。プライベートブラウザだと失敗することがあります。");
        }

        $state = $_COOKIE['line_login_state'];
        $collback_state = $request->state;
        if ($state !== $collback_state) {
            return redirect(route("login"))->with("messages.danger", "ログインに失敗しました。プライベートブラウザだと失敗することがあります。");
            //state不一致の場合はユーザーを自動ログインを行わない認可URLへリダイレクトする
            // $socialite = $this->redirectToProviderExcecute($request->provider, true);
            // return $socialite->redirect();
        }
        if (isset($_COOKIE['line_code_verifier'])) {
            // アクセストークンを発行
            $data = $this->createRequestData($request->code);
            //$response = $this->createPendingRequestInstance()->post($this->line_api_url, $data);
            //$content = $response->body();
            // curlじゃないと動かない?
            $headers = ['Content-Type: application/x-www-form-urlencoded'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->line_api_url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            $res = curl_exec($curl);
            curl_close($curl);
            $json = json_decode($res);
        }
        // 成功時の処理を以後に記載する
        $social_user = Socialite::driver($request->provider)->stateless()->user();

        $social_email = $social_user->getEmail();
        $social_name = $social_user->getName();

        if (!is_null($social_email)) {

            $test = 'ヒカキン';

            // $user = User::firstOrCreate([
            //     'email' => $social_email
            // ], [
            //     'email' => $social_email,
            //     'name' => $social_name,
            //     'password' => Hash::make(Str::random())
            // ]);

            // auth()->login($user);
            // return redirect('/dashboard');
        }

        return '必要な情報が取得できていません。';
    }

    //////////////////////////////////////////////// private method ////////////////////////////////////////////////
    /**
     * with()は1回までに収めておく
     *
     * @param string $provider
     * @param boolean $disable_auto_login_flag
     * @return \Laravel\Socialite\Contracts\Provider
     */
    private function redirectToProviderExcecute(string $provider, bool $disable_auto_login_flag = false): \Laravel\Socialite\Contracts\Provider
    {
        // lineソーシャルログイン時に友達追加を行えるようにする
        $bot_prompt = 'normal';
        // リプレイスアタック防止用
        $nonce  = Str::random(32);
        // stateは明示的に生成する。理由はlineからのコールバック後にstateの検証をしたい為。
        $state  = Str::random(32);

        $param_data = [
            'bot_prompt' => $bot_prompt,
            'nonce' => $nonce,
            'state' => $state,
        ];

        // PKCE対応
        $code_verifier = $this->generateCodeVerifier();
        $code_challenge = $this->generateCodeChallenge($code_verifier);
        $code_challenge_method = 'S256';
        //$param_data['code_challenge'] = $code_challenge;
        //$param_data['code_challenge_method'] = $code_challenge_method;

        if (empty($_SERVER['HTTPS'])) {
            // 30分間有効なクッキー
            // ngrok環境では、第6引数(secure属性)をTRUEに設定するとおかしくなる時がある、、、
            setcookie('line_login_state', $state, time() + 1800, '/', '', false, true);
            // code_verifierをクッキー保存する設計は精査する必要有
            //setcookie('line_code_verifier', $code_verifier, time() + 1800, '/', '', false, true);
        }

        if ($disable_auto_login_flag) {
            $param_data['disable_auto_login'] = true;
        }

        return Socialite::driver($provider)->with($param_data);
    }

    /**
     * code_verifier生成
     * 参考:https://qiita.com/sugamaan/items/50699432a65ad9e5829e
     * 
     * Laravel socialiteにもpkce対応関連の機能はあるみたいだが、現状はまだ公式ドキュメントにも
     * 記載が無く、どのように使えば良いか分からないので自作する
     * @param integer $byteLength
     * @return string code_verifier
     */
    private function generateCodeVerifier(int $byteLength = 32): string
    {
        $randomBytesString = openssl_random_pseudo_bytes($byteLength);
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
     * https://qiita.com/sugamaan/items/50699432a65ad9e5829e
     *
     * Laravel socialiteにもpkce対応関連の機能はあるみたいだが、現状はまだ公式ドキュメントにも
     * 記載が無く、どのように使えば良いか分からないので自作する
     * @param string $code_verifier
     * @return string code_verifier
     */
    private function generateCodeChallenge(string $code_verifier): string
    {
        $hash = hash('sha256', $code_verifier, true);
        return str_replace('=', '', strtr(base64_encode($hash), '+/', '-_'));
    }

    /**
     * リクエストデータ作成
     * @param CreateInputDto $input
     * @return array
     */
    private function createRequestData(string $code): array
    {
        $redirect_uri = config('services.line.redirect');
        $client_id = config('services.line.client_id');
        $client_secret = config('services.line.client_secret');


        $data = [
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $redirect_uri,
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "code_verifier" => $_COOKIE['line_code_verifier'],
        ];

        return $data;
    }
    /**
     * PendingRequestクラスを作成
     * @return PendingRequest
     */
    private function createPendingRequestInstance(string $token = null): PendingRequest
    {
        $pending_request = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]);

        return $pending_request;
    }


    /**
     * jsonを配列形式に変換
     *
     * @param string $json
     * @return stdClass
     */
    private function toArray(string $json): array
    {
        try {
            $content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw $e;
        }

        return $content;
    }
}
