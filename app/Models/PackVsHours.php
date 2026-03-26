<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackVsHours extends Model
{
    protected $table = 'hours';
    protected $fillable = [
        'packages_id'
    ];
}
