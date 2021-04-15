<?php


namespace App\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use InteractsWithSockets; use Dispatchable; use SerializesModels;

    public function __construct(){}

    public function broadcastOn()
    {
        return new Channel('notification');
    }

    public function broadcastWith()
    {
        return ['title'=>'This notification from ItSolutionStuff.com'];
    }

}
