<?php


namespace Seatplus\TelegramChannel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Seatplus\Notifications\Models\Outbox;
use Seatplus\TelegramChannel\Notifications\TelegramNotification;

class DispatchTelegramNotifications implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId() : string
    {
        return self::class;
    }

    public function tags() : array
    {
        return ['notifications', 'telegram'];
    }

    public function handle()
    {
        Outbox::cursor()->filter(fn ($outbox) => ! $outbox->is_sent)
            ->filter(function ($outbox) {
                return in_array(TelegramNotification::class, class_uses($outbox->notification));
            })->each(function ($outbox) {
                SendTelegramNotificationJob::dispatch($outbox)->onQueue('medium');
            });
    }
}
