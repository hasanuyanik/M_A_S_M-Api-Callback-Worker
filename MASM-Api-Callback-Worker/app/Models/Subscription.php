<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscription';

    protected $primaryKey = 'id';

    protected $fillable = [
        "receipt",
        "uid",
        "status",
        "expire_date"
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = false;
}
