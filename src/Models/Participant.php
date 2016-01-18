<?php

namespace DraperStudio\Messageable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $table = 'participants';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'last_read'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function model()
    {
        return $this->morphTo('participant');
    }
}
