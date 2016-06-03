<?php

namespace App\Events;

use App\Events\Event;
use App\Model\Video;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VideoWatched extends Event
{
    use SerializesModels;

    public $video;

    /**
     * Create a new event instance.
     * 
     * VideoWatched constructor.
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
