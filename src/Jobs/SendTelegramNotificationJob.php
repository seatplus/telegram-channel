<?php


namespace Seatplus\TelegramChannel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Seatplus\Notifications\Models\Outbox;
use Seatplus\Notifications\Notifications\DescribeNotificationInterface;
use Seatplus\TelegramChannel\Notifications\TelegramNotification;

class SendTelegramNotificationJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public DescribeNotificationInterface $notification;

    public function __construct(
        public Outbox $outbox
    ) {
        $this->notification = $this->outbox->notification;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->outbox->id;
    }

    public function tags() : array
    {
        return ['notifications', 'telegram'];
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new RateLimitedWithRedis('telegram')];
    }

    public function handle()
    {
        Notification::send([$this->outbox->notifiable], $this->notification);

        $this->outbox->update(['is_sent' => true]);
    }
}
