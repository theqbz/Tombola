<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        return $this->hasMany(Prize::class);
    }

    public function getDescriptionShort($limit=30) {
        $desc = strip_tags($this->description);
        return Str::limit($desc,$limit);
    }

    public function getAvatarUrl() {
        $titleParts = explode(' ',$this->title);
        return "https://ui-avatars.com/api/?name=Event+".$titleParts[0];
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
