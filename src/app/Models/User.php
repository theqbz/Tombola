<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const TEMPORARY = 0;
    const ACTIVE = 1;
    const FILLED = 2;

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

    public function getOwnEvents()
    {
        $userEvents = UserEvent::where(['user_id' => $this->id, 'access_type' => 1])->get();
        $events = array();
        foreach ($userEvents as $userEvent) {
            $events[] = Event::find($userEvent->event_id);
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
        return $this->status === self::FILLED;
    }

    public function listTickets($ticketStatus = 'active')
    {

        $tickets = array();
        foreach ($this->userEvents()->get()->all() as $userEvent) {
            switch ($ticketStatus) {
                case 'active':
                    $event = Event::find($userEvent->event_id)->whereDate('dt_end', '>', date('Y-m-d H:i'))->first();
                    break;
                case 'passive':
                    $event = Event::find($userEvent->event_id)->whereDate('dt_end', '<', date('Y-m-d H:i'))->first();
                    break;
            }
            if (isset($event)) {
                $tickets[] = array(
                    'event' => $event,
                    'tickets' => $userEvent->tickets->all()
                );
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
                if (isset($ticket->won_prize_id)) {
                    $event = Event::find($userEvent->event_id);
                    $prizes = Prize::find($ticket->won_prize_id)->get()->all();
                    $eventPrizes[] = array(
                        'event' => $event,
                        'prizes' => $prizes
                    );
                }
            }
        }

        return $eventPrizes;
    }

    /**
     * @return string $hash
     */

    public function getAccessCode()
    {
        return $this->hash;
    }
}