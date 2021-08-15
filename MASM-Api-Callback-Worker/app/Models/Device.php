<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    protected $primaryKey = 'deviceId';

    protected $fillable = [
        "uid",
        "appId",
        "language",
        "operating_system"
    ];

    public $timestamps = false;
}
