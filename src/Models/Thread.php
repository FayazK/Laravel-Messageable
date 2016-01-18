<?php

namespace DraperStudio\Messageable\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;

    protected $table = 'threads';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function creator()
    {
        return $this->messages()->oldest()->first()->creator;
    }

    public function getLatestMessage()
    {
        return $this->messages()->latest()->first();
    }

    public static function getAllLatest()
    {
        return static::latest('updated_at');
    }

    public function participantsIdsAndTypes($participant = null)
    {
        $participants = $this->participants()
                             ->withTrashed()
                             ->lists('participant_id', 'participant_type');

        if ($participant) {
            $participants[] = $participant;
        }

        return $participants;
    }

    public function scopeForModel($query, $participant)
    {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
            ->where('participants.participant_id', $participant->id)
            ->where('participants.participant_type', get_class($participant))
            ->where('participants.deleted_at', null)
            ->select('threads.*');
    }

    public function scopeForModelWithNewMessages($query, $participant)
    {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
            ->where('participants.participant_id', $participant->id)
            ->where('participants.participant_type', get_class($participant))
            ->whereNull('participants.deleted_at')
            ->where(function ($query) {
                $query->where('threads.updated_at', '>', 'participants.last_read')
                      ->orWhereNull('participants.last_read');
            })
            ->select('threads.*');
    }

    public function addMessage($data, Model $creator)
    {
        $message = (new Message())->fill(array_merge($data, [
            'creator_id' => $creator->id,
            'creator_type' => get_class($creator),
        ]));

        $this->messages()->save($message);

        return $message;
    }

    public function addMessages(array $messages)
    {
        foreach ($messages as $message) {
            $this->addMessage($message['data'], $message['creator']);
        }
    }

    public function addParticipant(Model $participant)
    {
        $participant = (new Participant())->fill([
            'participant_id' => $participant->id,
            'participant_type' => get_class($participant),
            'last_read' => new Carbon(),
        ]);

        $this->participants()->save($participant);

        return $participant;
    }

    public function addParticipants(array $participants)
    {
        foreach ($participants as $participant) {
            $this->addParticipant($participant);
        }
    }

    public function markAsRead($userId)
    {
        try {
            $participant = $this->getParticipantFromModel($userId);
            $participant->last_read = new Carbon();
            $participant->save();
        } catch (ModelNotFoundException $e) {
            // do nothing
        }
    }

    public function isUnread($participant)
    {
        try {
            $participant = $this->getParticipantFromModel($participant);

            if ($this->updated_at > $participant->last_read) {
                return true;
            }
        } catch (ModelNotFoundException $e) {
            // do nothing
        }

        return false;
    }

    public function getParticipantFromModel($participant)
    {
        return $this->participants()
                    ->where('participant_id', $participant->id)
                    ->where('participant_type', get_class($participant))
                    ->firstOrFail();
    }

    public function activateAllParticipants()
    {
        $participants = $this->participants()->withTrashed()->get();

        foreach ($participants as $participant) {
            $participant->restore();
        }
    }

    public function hasParticipant($participant)
    {
        return $this->participants()
                    ->where('participant_id', '=', $participant->id)
                    ->where('participant_type', '=', get_class($participant))
                    ->count() > 0;
    }
}
