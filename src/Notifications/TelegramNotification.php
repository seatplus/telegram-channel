<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\NewEveMail as NewEveMailBase;
use Seatplus\TelegramChannel\Model\TelegramNotifiable;
use Seatplus\TelegramChannel\Model\TelegramUser;

trait TelegramNotification
{
    public function via(mixed $notifiable): array
    {
        return [TelegramChannel::class];
    }

    abstract public function toTelegram(TelegramNotifiable $notifiable) : TelegramMessage;

    public static function getIcon(): string
    {
        return self::$icon;
    }

    public static function getTitle(): string
    {
        return self::$title;
    }

    public static function getDescription(): string
    {
        return self::$description;
    }

    public static function getPermission(): string
    {
        return isset(self::$permission) ? self::$permission : 'can subscribe to non-self notifications';
    }

    public static function getCorporationRole(): string
    {
        return isset(self::$corporation_role) ? self::$corporation_role : '';
    }
}
