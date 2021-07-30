<?php

use Illuminate\Support\Facades\Notification;
use Seatplus\Notifications\Models\Outbox;
use Seatplus\TelegramChannel\Jobs\SendTelegramNotificationJob;
use Seatplus\TelegramChannel\Model\TelegramUser;
use Seatplus\TelegramChannel\Notifications\RemoveCorporationMember;
use Seatplus\TelegramChannel\Notifications\TelegramNotification;

beforeEach(function () {
    $telegram_user = TelegramUser::create([
        'id' => $this->test_user->id,
        'user_id' => $this->test_user->id,
        'nickname' => $this->test_character->name,
        'name' => $this->test_character->name,
    ]);

    expect(TelegramUser::all())->toHaveCount(1);

    $notification = new RemoveCorporationMember(
        $this->test_character->corporation->name,
        $this->test_character->name,
        'start_date',
    );

    Outbox::create([
        'notifiable_type' => TelegramUser::class,
        'notifiable_id' => $telegram_user->id,
        'notification' => $notification,
        'is_sent' => false,
    ]);
});

it('can store eve mail notication in outbox', function () {
    expect(Outbox::all())->toHaveCount(1);
    expect(in_array(TelegramNotification::class, class_uses(Outbox::first()->notification)) )->toBeTrue();
    expect(Outbox::first()->notification instanceof RemoveCorporationMember)->toBeTrue();
    expect(Outbox::first()->notifiable instanceof TelegramUser)->toBeTrue();
});

it('sends message from job', function () {
    Notification::fake();

    Notification::assertNothingSent();

    (new SendTelegramNotificationJob(Outbox::first()))->handle();

    Notification::assertSentTo(
        [Outbox::first()->notifiable],
        RemoveCorporationMember::class
    );
});
