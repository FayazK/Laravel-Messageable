<?php

/*
 * This file is part of Laravel Messageable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Messageable\Traits;

use DraperStudio\Messageable\Models\Message;
use DraperStudio\Messageable\Models\Participant;
use DraperStudio\Messageable\Models\Thread;

/**
 * Class Messageable.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
trait Messageable
{
    /**
     * @return mixed
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'creator');
    }

    /**
     * @return mixed
     */
    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'participants', 'participant_id');
    }

    /**
     * @return int
     */
    public function newMessagesCount()
    {
        return count($this->threadsWithNewMessages());
    }

    /**
     * @return array
     */
    public function threadsWithNewMessages()
    {
        $threadsWithNewMessages = [];
        $participants = Participant::where('participant_id', $this->id)
                                    ->where('participant_type', get_class($this))
                                    ->lists('last_read', 'thread_id');

        if ($participants) {
            $threads = Thread::whereIn('id', array_keys($participants->toArray()))->get();

            foreach ($threads as $thread) {
                if ($thread->updated_at > $participants[$thread->id]) {
                    $threadsWithNewMessages[] = $thread->id;
                }
            }
        }

        return $threadsWithNewMessages;
    }
}
