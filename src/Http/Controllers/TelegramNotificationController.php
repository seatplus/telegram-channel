<?php


namespace Seatplus\TelegramChannel\Http\Controllers;

use Composer\Autoload\ClassMapGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Seatplus\TelegramChannel\Model\TelegramChannel;
use Seatplus\TelegramChannel\Model\TelegramUser;
use Seatplus\TelegramChannel\Notifications\TelegramNotification;
use Seatplus\TelegramChannel\Services\Telegram;

class TelegramNotificationController extends Controller
{
    public function index()
    {
        $channels = collect(config('notification.channels'))
            ->map(function ($channel) {
                $channel['current'] = $channel['route'] === 'telegram.notification.index';

                return $channel;
            });

        $notifications = collect(ClassMapGenerator::createMap(__DIR__ . '/../../Notifications'))
            // Remove abstract notification class
            ->filter(fn ($value, $key) => $key !== TelegramNotification::class)
            ->map(fn ($value, $key) => [
                'icon' => $key::getIcon(),
                'title' => $key::getTitle(),
                'description' => $key::getDescription(),
                'class' => $key,
            ]);

        $notifiable = TelegramUser::query()
            ->where('user_id', auth()->user()->getAuthIdentifier())
            ->get()
            ->map(fn ($telegram_user) => [
                'notifiable_type' => TelegramUser::class,
                'notifiable_id' => $telegram_user->id,
            ])
            ->first();

        return inertia('Notifications/TelegramIndex', [
            'isSetup' => (bool) data_get(config('services.telegram'), 'client_secret'),
            'channels' => $channels,
            'canSubscribeToChannels' => auth()->user()->can('can subscribe to non-self notifications'),
            'notifiable' => $notifiable,
            'notifications' => $notifications->toArray(),
        ]);
    }

    public function getChannels()
    {
        $telegram_user = TelegramUser::query()
           ->where('user_id', auth()->user()->getAuthIdentifier())
           ->get()
           ->map(fn ($telegram_user) => [
               'name' => $telegram_user->name,
               'notifiable' => [
                   'notifiable_type' => TelegramUser::class,
                   'notifiable_id' => $telegram_user->id,
               ],
           ])
           ->first();


        $channels = TelegramChannel::all()->map(fn ($channel) => [
            'name' => $channel->name,
            'notifiable' => [
                'notifiable_id' => $channel->id,
                'notifiable_type' => TelegramChannel::class,
            ],
        ]);

        return [
            $telegram_user,
            ...$channels->toArray(),
        ];
    }

    public function registerChannel(Request $request, Telegram $telegram)
    {
        $validated_data = $request->validate([
            'id' => ['required', 'string'],
        ]);

        $response = $telegram->getUpdates([
            'allowed_updates' => 'message',
        ]);

        $result = data_get(json_decode($response->getBody()->getContents(), true), 'result', []);

        $filtered = Arr::where($result, fn ($value) => Str::contains(data_get($value, 'message.text'), $validated_data['id']));

        abort_unless((bool) $filtered, 403, 'Could not find telegram channel, are you positive you wrote the presented code?');

        $chat_id = data_get(head($filtered), 'message.chat.id');
        $chat_title = data_get(head($filtered), 'message.chat.title');

        TelegramChannel::firstOrCreate([
            'id' => $chat_id,
        ], [
            'name' => $chat_title,
        ]);

        return redirect()->route('telegram.notification.index');
    }
}
