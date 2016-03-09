<?php

/*
 * This file is part of Laravel Messageable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Messageable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class Message extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $touches = ['thread'];

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function creator()
    {
        return $this->morphTo('creator');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(Participant::class, 'thread_id', 'thread_id');
    }

    /**
     * @return mixed
     */
    public function recipients()
    {
        return $this->participants()
                    ->where('participant_id', '!=', $this->participant_id)
                    ->where('participant_type', '!=', $this->participant_type);
    }
}
