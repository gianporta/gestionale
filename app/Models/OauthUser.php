<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OauthUser extends Model
{
    use HasFactory;

    protected $table = 'oauth_user';
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $columns = Schema::getColumnListing($this->getTable());
        $this->fillable = $columns;
    }
}