<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    protected $fillable = ['creator_id','title','description','starts_at','location','capacity','category_id'];
    protected $casts = ['starts_at'=>'datetime'];
    public function organiser(){return $this->belongsTo(User::class,'creator_id');}
    public function scopeUpcomingEvents($evt){
        return $evt->where('starts_at','>',now()); 
    }
    public function category(){ return $this->belongsTo(Category::class); }
    
    public function isCapacityFull():bool{
        return $this->bookings()->count() >= $this->capacity;
    }

    public function bookings(){return $this->hasMany(Booking::class); }
}
