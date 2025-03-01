<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;


    protected $fillable = [
        "event_name",
        "checkIn_start",
        "checkIn_end",
        "checkOut_start",
        "checkOut_end",
        "isWholeDay",
        "afternoon_checkIn_start",
        "afternoon_checkIn_end",
        "afternoon_checkOut_start",
        "afternoon_checkOut_end",
        "date",
        "admin_id",
        "status"
    ];

}
