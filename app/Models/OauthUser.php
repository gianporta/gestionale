<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthUser extends Model
{
    use HasFactory;

    protected $table = 'oauth_user';

    protected $fillable = ['id', 'email', 'user', 'email', 'psw', 'is_active', 'expiration'];
}
