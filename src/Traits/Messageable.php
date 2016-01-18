<?php

namespace DraperStudio\Messageable\Traits;

use DraperStudio\Messageable\Models\Message;
use DraperStudio\Messageable\Models\Participant;
use DraperStudio\Messageable\Models\Thread;

trait Messageable
{
    public function messages()
    {
        return $this->morphMany(Message::class, 'creator');
    }

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'participants', 'participant_id');
    }

    public function newMessagesCount()
    {
        return count($this->threadsWithNewMessages());
    }

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