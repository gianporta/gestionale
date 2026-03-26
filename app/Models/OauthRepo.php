<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthRepo extends Model
{

    protected $table = 'oauth_repo';

    protected $fillable = ['id', 'link_site', 'id_user', 'id_repo', 'is_active', 'expiration'];
}
