<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataImportCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $status;
    public $userId;
    /**
     * Create a new event instance.
     */
    public function __construct($message, $status, $userId)
    {
        $this->message = $message;
        $this->status = $status;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('import-notifications.' . $this->userId);
    }
}
