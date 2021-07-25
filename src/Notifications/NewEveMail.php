<?php


namespace Seatplus\TelegramChannel\Notifications;


use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Seatplus\TelegramChannel\Model\TelegramUser;

class NewEveMail extends TelegramNotification
{
    const ICON_STRING = 'InboxInIcon';
    const TITLE_STRING = 'New Eve Mail Notification';
    const DESCRIPTION_STRING = 'Get notified whenever you receive a new eve mail';
    const PERMISSION_STRING = 'can subscribe to notifications';
    const CORPORATION_ROLE_STRING = '';

    public function toTelegram(TelegramUser $telegramUser): TelegramMessage
    {

        $text = implode(' \n ', [
            '*New mail:*',
            "From: *{$this->sender_name}*",
            "Subject: _{$this->subject}_"
        ]);

        return TelegramMessage::create()
            ->to($telegramUser->id)
            ->content("*New mail:*\nFrom: *{$this->sender_name}*\nSubject: _{$this->subject}_")
            ->button('View mail', $this->route);
    }

    public static function getIcon(): string
    {
        return self::ICON_STRING;
    }

    public static function getTitle(): string
    {
        return self::TITLE_STRING;
    }

    public static function getDescription(): string
    {
        return self::DESCRIPTION_STRING;
    }

    public static function getPermission(): string
    {
        return self::PERMISSION_STRING;
    }

    public static function getCorporationRole(): string
    {
        return self::CORPORATION_ROLE_STRING;
    }
}