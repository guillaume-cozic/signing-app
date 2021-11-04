<?php


namespace App\Signing\Notifications\Domain\Events;


use App\Signing\Shared\Services\ContextService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use InteractsWithSockets; use Dispatchable; use SerializesModels;

    public function __construct(
        public string $message,
        public string $title,
        public string $avatar,
        public string $level
    ){}

    public function broadcastOn()
    {
        return new PrivateChannel('notification.'.app(ContextService::class)->get()->sailingClubId());
    }
}
