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

    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }

    public function eventTicketGroups()
    {
        return $this->hasMany(EventTicketGroup::class);
    }

    public function prizes()
    {
        return $this->hasMany(Prize::class);
    }

    public function getDescriptionShort($limit = 30)
    {
        $desc = strip_tags($this->description);
        return Str::limit($desc, $limit);
    }

    public function getAvatarUrl()
    {
        $titleParts = explode(' ', $this->title);
        return "https://ui-avatars.com/api/?name=Event+" . $titleParts[0];
    }

    public function getEndDate()
    {
        return $this->dt_end->format('Y.m.d H:i');
    }

    public function countTickets()
    {
        $tickets = array();
        $userEvents = $this->userEvents->all();
        foreach ($userEvents as $userEvent) {
            $tickets = array_merge($tickets, $userEvent->tickets->all());
        }
        return count($tickets);
    }

    public function hasMoreTickets()
    {
        $eventTicketGroup = $this->eventTicketGroups->first();
        $limit = $eventTicketGroup->limit;
        return ($limit === 0 || $this->countTickets() < $limit);
    }

    public function isAvailable()
    {
        return $this->dt_end > date('Y-m-d H:i');
    }

    public function hasMultipleColors()
    {
        return (count($this->eventTicketGroups()->get()->all()) > 1);
    }

    public function getAvailableTickets($color = null)
    {
        $limit = 0;
        $availableTickets = 0;
        $tickets = array();

        if ($color) {
            $eventGroup = $this->eventTicketGroups->where('ticket_color', $color)->first();
            $limit = $eventGroup->limit;
            $userEvents = $this->userEvents->all();

            foreach ($userEvents as $userEvent) {
                $tickets = array_merge($tickets, $userEvent->tickets->where('color', $color)->pluck('value')->all());
            }

        } else {
            $eventGroup = $this->eventTicketGroups->first();
            $limit = $eventGroup->limit;
            $userEvent = $this->userEvents->first();
            $tickets = array_merge($tickets, $userEvent->tickets->pluck('value')->all());
        }

        if ($limit != 0) {
            $availableTickets = range(0, $limit);
            unset($availableTickets[0]);
            foreach ($tickets as $_idx => $ticket) {
                if ($idx = array_search($ticket, $availableTickets)) {
                    unset($availableTickets[$idx]);
                }
            }
        }

        return $availableTickets;

    }

    public function getAvailableColors() {
        $colors = array();
        foreach ( $this->eventTicketGroups->pluck('ticket_color')->all() as $color) {
            $colors[__($color)] = $color;
        }
        return $colors;
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
