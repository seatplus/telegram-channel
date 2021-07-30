<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\NewEveMail as NewEveMailBase;
use Seatplus\TelegramChannel\Model\TelegramNotifiable;

class NewEveMail extends NewEveMailBase
{
    use TelegramNotification;

    public static string $icon = 'InboxInIcon';
    public static string $title = 'New Eve Mail Notification';
    public static string $description = 'Get notified whenever you receive a new eve mail';

    public function toTelegram(TelegramNotifiable $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->id)
            ->content("*New mail:*\nFrom: *{$this->sender_name}*\nSubject: _{$this->subject}_")
            ->button('View mail', $this->route);
    }
}
