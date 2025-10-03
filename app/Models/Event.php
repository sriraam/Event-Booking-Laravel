<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Event model represents a scheduled activity created by an organiser.
 */
class Event extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = ['creator_id','title','description','starts_at','location','capacity','category_id'];
    protected $casts = ['starts_at'=>'datetime'];
    
    /**
     * Get the organiser of the event.
     *
     * @return BelongsTo
     */
    public function organiser(){
        return $this->belongsTo(User::class,'creator_id');
    }

    /**
     * Scope a query to only include upcoming events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcomingEvents($evt){
        return $evt->where('starts_at','>',now()); 
    }

    /**
     * Get the categories associated with the event.
     *
     * @return BelongsToMany
     */
    public function categories(){ 
        return $this->belongsToMany(Category::class); 
    }
    
    /**
     * Check if the event has reached full capacity.
     *
     * @return bool
     */
    public function isCapacityFull():bool{
        return $this->bookings()->count() >= $this->capacity;
    }

    /**
     * Get the bookings for the event.
     *
     * @return HasMany
     */
    public function bookings(){
        return $this->hasMany(Booking::class); 
    }
    /**
     * Alias for organiser relationship.
     *
     * @return BelongsTo
     */
    public function creator(){ 
        return $this->belongsTo(User::class, 'creator_id');
    }
}
