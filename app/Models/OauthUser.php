<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthUser extends Model
{
    use HasFactory;

    protected $table = 'oauth_user';

    protected $fillable = ['id', 'link_site', 'user', 'email', 'psw', 'is_active', 'expiration'];
}
