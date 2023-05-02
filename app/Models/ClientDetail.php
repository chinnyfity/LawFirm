<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'staff_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'pics',
        'date_profiled',
        'legal_counsel',
        'dob',
        'case_details',
    ];
}