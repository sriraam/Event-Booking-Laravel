<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    protected $fillable = ['creator_id','title','starts_at','location','capacity'];
    protected $casts = ['starts_at'=>'datetime'];
    public function organiser(){return $this->belongsTo(User::class,'creator_id');}
    public function scopeUpcomingEvents($evt){
        return $evt->where('starts_at','>',now()); 
    }
}
