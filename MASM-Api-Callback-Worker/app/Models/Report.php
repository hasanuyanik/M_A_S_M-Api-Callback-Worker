<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';

    protected $primaryKey = 'id';

    protected $fillable = [
        "appId",
        "day",
        "operating_system",
        "started_piece",
        "renewed_piece",
        "canceled_piece"
    ];

    public $timestamps = false;
}
