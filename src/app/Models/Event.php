<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'description',
        'dt_start',
        'dt_end',
        'location',
        'is_public',
        'auto_ticket'
    ];

    public function userEvents() {
        return $this->hasMany(UserEvent::class);
    }

    public function eventTicketGroups() {
        return $this->hasMany(EventTicketGroup::class);
    }

    public function prizes() {
        return $this->hasMany(EventTicketGroup::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'dt_start' => 'datetime',
        'dt_end' => 'datetime',
    ];
}
