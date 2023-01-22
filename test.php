namespace App\Http\Controllers;

use App\Models\LineUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use LINE\LINEBot;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineRegistrationController extends Controller
{
private $channel_secret, $access_token;

public function __construct() {

$this->channel_secret = env('LINE_CHANNEL_SECRET');
$this->access_token = env('LINE_ACCESS_TOKEN');

}

public function image($size) {

$path = storage_path('app/images/line_user_registration.png');
$image = \Image::make($path);

if($size < 1040) { $image->resize($size, $size);

    }

    return $image->response();

    }

    public function callback(Request $request) {

    $socialite_user = Socialite::driver('line')->stateless()->user();
    $socialite_id = $socialite_user->getId();
    $socialite_email = $socialite_user->getEmail();
    $socialite_name = $socialite_user->getName();
    $line_user = LineUser::where('line_id', $socialite_id)->first();

    if(!is_null($line_user) && !is_null($socialite_email)) {

    \DB::beginTransaction();

    try {

    $user = User::firstOrNew(['email' => $socialite_email]);
    $user->email = $socialite_email;
    $user->name = $socialite_name;
    $user->password = Hash::make(Str::random()); // パスワードはランダム
    $user->save();

    $line_user->user_id = $user->id;
    $line_user->save();

    $line_id = $line_user->line_id;
    $client = new CurlHTTPClient($this->access_token);
    $bot = new LINEBot($client, ['channelSecret' => $this->channel_secret]);
    $text_message = new TextMessageBuilder('会員登録が完了しました！');
    $bot->pushMessage($line_id, $text_message);

    auth()->login($user); // 自動ログイン
    \DB::commit();

    return '会員登録が完了しました！';

    } catch (\Exception $e) {

    // ここでエラー処理
    \DB::rollBack();

    }

    }

    return '必要な情報が取得できていません。';

    }

    public function webhook(Request $request) {

    $request_body = $request->getContent();
    $hash = hash_hmac('sha256', $request_body, $this->channel_secret, true);
    $signature = base64_encode($hash);

    if($signature === $request->header('X-Line-Signature')) { // ここでLINEからの送信を検証してます

    $client = new CurlHTTPClient($this->access_token);
    $bot = new LINEBot($client, ['channelSecret' => $this->channel_secret]);

    try {

    $events = $bot->parseEventRequest($request_body, $signature);

    foreach($events as $event) {

    $line_id = $event->getEventSourceId();
    $reply_token = $event->getReplyToken(); // 返信用トークン

    if($event instanceof FollowEvent) { // お友達登録されたとき

    // DBへ取得情報を保存
    $mode = $event->getMode();
    $profile = $bot->getProfile($line_id)->getJSONDecodedBody();
    $display_name = $profile['displayName'];

    $line_user = LineUser::firstOrNew(['line_id' => $line_id]);
    $line_user->mode = $mode;
    $line_user->display_name = $display_name;
    $line_user->save();

    // 自動返信（登録リンク送信）
    $width = 1040;
    $height = 1040;
    $alt_text = '会員登録できます！'; // 代替テキスト
    $base_url = route('line.registration.image', ''); // 画像URL
    $base_size = new BaseSizeBuilder($height, $width); // 基本画像のサイズ

    $x = 0;
    $y = 0;
    $area = new AreaBuilder($x, $y, $width, $height);
    $link_url = Socialite::driver('line') // LINEログインのURL
    ->redirect()
    ->getTargetUrl();
    $image_map_actions = [ new ImagemapUriActionBuilder($link_url, $area)];

    $image_map_message = new ImagemapMessageBuilder(
    $base_url,
    $alt_text,
    $base_size,
    $image_map_actions
    );
    $bot->replyMessage($reply_token, $image_map_message);

    } else if($event instanceof UnfollowEvent) { // お友達登録が解除されたとき

    LineUser::where('line_id', $line_id)->delete();

    }

    }

    } catch (\Exception $e) {

    // ここでエラー処理

    }

    }

    }
    }