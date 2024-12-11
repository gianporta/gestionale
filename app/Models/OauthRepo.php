<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthRepo extends Model
{
    use HasFactory;

    protected $table = 'oauth_repo';
    protected $fillable = ['id_user', 'id_repo', 'is_active', 'expiration'];

    public function repo()
    {
        return $this->belongsTo(Repo::class, 'id_repo');
    }

    public function user()
    {
        return $this->belongsTo(OauthUser::class, 'id_user');
    }
}