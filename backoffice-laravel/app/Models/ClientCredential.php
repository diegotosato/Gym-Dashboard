<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Ai\Concerns\HasConversations;
use Laravel\Sanctum\HasApiTokens;

class ClientCredential extends Authenticatable
{
    use HasApiTokens, HasConversations;

    protected $table = 'client_credentials';

    protected $fillable = [
        'client_id',
        'username',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
