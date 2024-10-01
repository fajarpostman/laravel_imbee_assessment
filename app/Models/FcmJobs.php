<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmJobs extends Model
{
    use HasFactory;

    protected $table = 'fcm_jobs';

    protected $fillable = [
        'identifier', 
        'deliverAt'
    ];

    public $timestamps = true;
}
