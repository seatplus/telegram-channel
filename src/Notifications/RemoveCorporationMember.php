<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\DeleteCorporationMember;
use Seatplus\TelegramChannel\Model\TelegramNotifiable;

class RemoveCorporationMember extends DeleteCorporationMember
{
    use TelegramNotification;

    public static string $icon = 'UserRemoveIcon';
    public static string $title = 'Leaving Corporation Member Notification';
    public static string $description = 'Get notified whenever a member leaves the selected corporation.';
    public static string $corporation_role = 'Director';

    public function toTelegram(TelegramNotifiable $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->id)
            ->content("*A member has left*\n*{$this->character}* left *{$this->corporation}*\nStart date: _{$this->start_date}_");
    }
}
