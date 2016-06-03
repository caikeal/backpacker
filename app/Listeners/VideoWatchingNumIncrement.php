<?php

namespace App\Listeners;

use App\Events\VideoWatched;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VideoWatchingNumIncrement
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VideoWatched  $event
     * @return void
     */
    public function handle(VideoWatched $event)
    {
        $event->video->increment('view_num', 1);
    }
}
