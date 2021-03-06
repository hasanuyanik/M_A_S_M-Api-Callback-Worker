<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    protected $primaryKey = 'uid';

    protected $fillable = [
        "uid",
        "appId",
        "language",
        "operating_system",
        "token"
    ];

    public $timestamps = false;
}
