<?php

namespace DraperStudio\Messageable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'messages';

    protected $touches = ['thread'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function creator()
    {
        return $this->morphTo('creator');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'thread_id', 'thread_id');
    }

    public function recipients()
    {
        return $this->participants()
                    ->where('participant_id', '!=', $this->participant_id)
                    ->where('participant_type', '!=', $this->participant_type);
    }
}
