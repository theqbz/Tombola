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

    public function getEndDate() {
        return $this->dt_end->format('Y.m.d H:i');
    }

    public function countTickets() {
        $tickets = array();
        $userEvents = $this->userEvents->all();
        foreach ($userEvents as $userEvent) {
            $tickets = array_merge($tickets,$userEvent->tickets->all());
        }
        return count($tickets);
    }

    public function hasMoreTickets() {
        $eventTicketGroup = $this->eventTicketGroups->first();
        $limit = $eventTicketGroup->limit;

        return $this->countTickets() < $limit;
    }

    public function isAvailable() {
        return  $this->dt_end > date('Y-m-d H:i');
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
