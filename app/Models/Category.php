<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // parametre necessaire pour remplir ces champs (update et store)
    protected $fillable = ['name', 'picture', 'description', 'slug', 'react_icon'];

    public function gigs(){
        // A category can have several gigs
        return $this->hasMany(Gig::class);
    }
}


