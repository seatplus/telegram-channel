<?php


namespace Seatplus\TelegramChannel\Http\Controllers;

use Composer\Autoload\ClassMapGenerator;
use Seatplus\TelegramChannel\Model\TelegramUser;
use Seatplus\TelegramChannel\Notifications\TelegramNotification;

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
            'notifiable' => $notifiable,
            'notifications' => $notifications->toArray(),
        ]);
    }
}
