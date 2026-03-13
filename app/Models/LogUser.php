<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogUser extends Model
{
    use HasFactory;

    protected $table = 'log_user';

    protected $fillable = [
        'user_id',
        'action',
        'url',
        'method',
        'ip',
        'user_agent',
    ];
}
