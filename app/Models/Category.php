<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Category model represents a classification for events.
 *
 * Each category has a name, a color for UI styling.
 * Categories are linked to events via a many-to-many relationship.
 *
 */
class Category extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable=['name','color'];

    /**
     * Get the events associated with this category.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events(){ 
        return $this->belongsToMany(Event::class); 
    }
}
