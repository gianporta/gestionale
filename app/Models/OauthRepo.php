<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthRepo extends Model
{
    use HasFactory;

    protected $table = 'oauth_repo';

    protected $fillable = ['id', 'id_user', 'id_repo','is_active', 'expiration'];
}
