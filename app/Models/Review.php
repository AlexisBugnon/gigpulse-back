<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['gig_id', 'user_id', 'rating', 'comment'];
    public function user(){
        // A review can only belong to one user
        return $this->belongsTo(User::class);
    }

    public function gig(){
        // A review can only belong to one gig
        return $this->belongsTo(Gig::class);
    }
}
