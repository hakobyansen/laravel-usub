<?php

namespace Usub\Models;

use Illuminate\Database\Eloquent\Model;

class UsubToken extends Model
{
    protected $table = 'usub_tokens';

    protected $fillable = [
        'user1',
        'user2',
        'token',
        'redirect_to',
        'expires_at'
    ];
}