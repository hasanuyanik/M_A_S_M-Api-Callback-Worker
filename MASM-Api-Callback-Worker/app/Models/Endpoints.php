<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoints extends Model
{
    use HasFactory;

    protected $table = 'endpoints';

    protected $primaryKey = 'id';

    protected $fillable = [
        "name",
        "endpoint"
    ];

    public $timestamps = false;
}
