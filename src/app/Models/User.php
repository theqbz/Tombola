<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const TEMPORARY = 0;
    const ACTIVE = 1;
    const FILLED = 2;
    const ADMIN = 3;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'date_of_birth',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'exp_date' => 'datetime',
        'date_of_birth'
    ];

    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }

    public function getOwnEvents($status = 'active', $access = 1, $limit = null)
    {
        $userEvents = UserEvent::where(['user_id' => $this->id, 'access_type' => $access])->get();
        $events = array();
        foreach ($userEvents as $userEvent) {
            $event = Event::find($userEvent->event_id);
            if ($status === 'all') {
                $events[$event->id] = $event;
            } else {
                if ($status === 'active' && $event->isAvailable()) {

                    $events[$event->id] = $event;
                }
                if ($status === 'passive' && !$event->isAvailable()) {
                    $events[$event->id] = $event;
                }
            }

        }

        if (count($events) && !is_null($limit)) {
            $events = array_splice($events, 0, $limit);
        }
        return $events;
    }

    public function isEditor($id)
    {
        $userEvent = UserEvent::where(['user_id' => $this->id, 'event_id' => $id, 'access_type' => 1])->first();
        if ($userEvent) {
            return true;
        } else {
            return false;
        }
    }

    public function canCreateEvent()
    {
        return $this->status >= self::FILLED;
    }

    public function isAdmin()
    {
        return $this->status === self::ADMIN;
    }

    public function listTickets($ticketStatus = 'active', $limit = null)
    {
        $userEvents = $this->userEvents()->where('access_type', 0)->get()->all();
        $tickets = array();
        $ticketcount = 0;
        foreach ($userEvents as $userEvent) {
            switch ($ticketStatus) {
                case 'all':
                    $event = Event::where('id', $userEvent->event_id)->first();
                    break;
                case 'active':
                    $event = Event::where('id', $userEvent->event_id)->where('dt_end', '>', Carbon::now('Europe/Budapest')->format('Y-m-d H:i'))->first();
                    break;
                case 'winner':
                case 'passive':
                    $event = Event::where('id', $userEvent->event_id)->where('dt_end', '<', Carbon::now('Europe/Budapest')->format('Y-m-d H:i'))->first();
                    break;
            }
            if (isset($event)) {
                if ($ticketStatus === 'winner') {
                    $_tickets = $userEvent->tickets->whereNotNull('won_prize_id')->all();
                } else {
                    $_tickets = $userEvent->tickets->all();
                }
                if (count($_tickets)) {
                    $ticketcount += count($_tickets);
                    if ($ticketcount > $limit) {
                        array_splice($_tickets, 0, $limit);
                    }
                    $tickets[$event->id] = array(
                        'event' => $event,
                        'tickets' => $_tickets
                    );

                }
            }
        }
        return $tickets;
    }

    public function listPrizes()
    {
        $eventPrizes = array();
        foreach ($this->userEvents()->get()->all() as $userEvent) {
            $tickets = Ticket::where('user_event_id', $userEvent->id)->get()->all();
            foreach ($tickets as $ticket) {
                if (!is_null($ticket->won_prize_id)) {
                    $event = Event::find($userEvent->event_id);
                    $prizes = Prize::where('winner_ticket_id', $ticket->id)->get()->all();

                    $eventPrizes[] = array(
                        'event' => $event,
                        'prizes' => $prizes
                    );
                }
            }
        }

        return $eventPrizes;
    }

    public function hasTicketForEvent($event)
    {
        $userEvents = $this->userEvents()->where(['access_type' => 0, 'event_id' => $event->id])->get()->all();
        return count($userEvents);

    }

    /**
     * @return string $hash
     */

    public function getAccessCode()
    {
        return $this->hash;
    }
}
