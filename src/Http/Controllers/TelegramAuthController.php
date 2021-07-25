<?php


namespace Seatplus\TelegramChannel\Http\Controllers;


use Laravel\Socialite\Facades\Socialite;
use Seatplus\TelegramChannel\Model\TelegramUser;
use SocialiteProviders\Manager\Config;

class TelegramAuthController extends Controller
{
    public function infos()
    {
        return [
            'botname' => config('services.telegram.bot'),
            'callback_url' => config('services.telegram.redirect')
        ];
    }

    public function handle()
    {

        $config = new Config(
            config('services.telegram')['client_id'],
            config('services.telegram')['client_secret'],
            config('services.telegram')['redirect'],
            ['bot' => config('services.telegram')['bot']]
        );

        //$user = Socialite::driver('telegram')->setConfig($config)->user();
        $user = Socialite::with('telegram')->user();

        TelegramUser::firstOrCreate([
            'id' => data_get($user, 'id')
        ], [
            'user_id' => auth()->user()->getAuthIdentifier(),
            'nickname' => data_get($user, 'nickname'),
            'name' => data_get($user, 'name')
        ]);

        return redirect()->route('telegram.notification.index');
    }
}