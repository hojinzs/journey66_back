<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderToken extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function updateToken($new_token)
    {
        $this->token_type = $new_token->token_type;
        $this->access_token = $new_token->access_token;
        $this->refresh_token = $new_token->refresh_token;
        $this->expires_in = $new_token->expires_in;
        $this->update();

        return $this;
    }
}
