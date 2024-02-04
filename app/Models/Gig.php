<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
    use HasFactory;
    // Necessary parameter to fill these fields (update and store)
    protected $fillable = ['title', 'user_id', 'picture', 'description', 'price', 'category_id', 'is_active', 'slug'];

    public function user(){
        // A gig can only belong to one user
        return $this->belongsTo(User::class);
    }
    public function reviews(){
        // A gig can have several reviews
        return $this->hasMany(Review::class);
    }
    public function category(){
        // A gig can only belong to one category
        return $this->belongsTo(Category::class);
    }
    public function tags(){
        // A gig can have several tags
        return $this->belongsToMany(Tag::class, 'gig_tag');
    }
    public function favoritedBy(){
        // A gig can be favorited by several users
        return $this->belongsToMany(User::class, 'gig_user');
    }
}

