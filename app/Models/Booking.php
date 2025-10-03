<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Booking model represents a reservation made by a user for a specific event.
 */
class Booking extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable=['user_id','event_id'];

     /**
     * Get the user who made the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){ return $this->belongsTo(User::class); }
    
    /**
     * Get the event that was booked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(){ return $this->belongsTo(Event::class); }
}
