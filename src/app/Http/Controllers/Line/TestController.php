<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Models\LinebotChannel;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class TestController extends Controller
{
    /**
     * @return void
     */
    public function index()
    {
        $linebot_channel = LinebotChannel::first();
        $httpClient = new CurlHTTPClient($linebot_channel->access_token);
        $bot = new LINEBot($httpClient, ['channelSecret' => $linebot_channel->channel_secret]);

        $textMessageBuilder = new TextMessageBuilder('hello');

        $linebot_channel_users = $linebot_channel->users()->where('friend_flag', '=', true)->get();

        foreach ($linebot_channel_users as $key => $linebot_channel_user) {
            $response = $bot->pushMessage("{$linebot_channel_user->pivot->line_user_id}", $textMessageBuilder);
        }

        var_dump('テスト');
    }

}
