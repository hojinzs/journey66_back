<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    /**
     * Get the like model.
     */
    public function like_what()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeUser($query,User $user)
    {
        return $query->where('user_id',$user->id);
    }
}
