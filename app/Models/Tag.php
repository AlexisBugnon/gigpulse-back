<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function gigs(){
        // A gig can only belong to one category
        return $this->belongsToMany(Gig::class, 'gig_tag');
    }
}
