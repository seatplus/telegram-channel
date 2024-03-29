<?php


namespace Seatplus\TelegramChannel\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\Notifications\Notifications\NewCorporationMember as NewCorporationMemberBase;
use Seatplus\TelegramChannel\Model\TelegramNotifiable;

class NewCorporationMember extends NewCorporationMemberBase
{
    use TelegramNotification;

    public static string $icon = 'UserAddIcon';
    public static string $title = 'New Corporation Member Notification';
    public static string $description = 'Get notified whenever a new member joins the selected corporation.';
    public static string $corporation_role = 'Director';

    public function toTelegram(TelegramNotifiable $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->id)
            ->content("*A new member has joined*\n*{$this->character}* joined *{$this->corporation}*\nStart date: _{$this->start_date}_");
    }
}
