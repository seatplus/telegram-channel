<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\NewEveMail as NewEveMailBase;
use Seatplus\TelegramChannel\Model\TelegramUser;

abstract class TelegramNotification extends NewEveMailBase
{
    public function via(mixed $notifiable): array
    {
        return [TelegramChannel::class];
    }

    abstract public function toTelegram(TelegramUser $telegramUser) : TelegramMessage;
}
