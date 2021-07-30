<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\DeleteCorporationMember;
use Seatplus\TelegramChannel\Model\TelegramNotifiable;

class RemoveCorporationMember extends DeleteCorporationMember
{

    use TelegramNotification;

    static string $icon = 'UserRemoveIcon';
    static string $title = 'Leaving Corporation Member Notification';
    static string $description = 'Get notified whenever a member leaves the selected corporation.';
    static string $corporation_role = 'Director';

    public function toTelegram(TelegramNotifiable $notifiable): TelegramMessage
    {

        return TelegramMessage::create()
            ->to($notifiable->id)
            ->content("*A member has left*\n*{$this->character}* left *{$this->corporation}*\nStart date: _{$this->start_date}_");
    }
}
