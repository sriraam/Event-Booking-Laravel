<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Waitlist model represents a user's request to join an event that is currently full.
 */
class Waitlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable=['user_id','event_id'];

    /**
     * Get the user associated with this waitlist entry.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

     /**
     * Get the event associated with this waitlist entry.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(){
        return $this->belongsTo(event::class);
    }
}
