<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
class OauthRepo extends Model
{
    use HasFactory;

    protected $table = 'oauth_repo';
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $columns = Schema::getColumnListing($this->getTable());
        $this->fillable = $columns;
    }

    public function repo()
    {
        return $this->belongsTo(Repo::class, 'id_repo');
    }

    public function user()
    {
        return $this->belongsTo(OauthUser::class, 'id_user');
    }
}