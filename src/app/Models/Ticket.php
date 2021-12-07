<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_event_id',
        'color',
        'value',
        'won_prize_id'
    ];


    public function userEvent()
    {
        return $this->belongsTo(UserEvent::class);
    }

    public function isWinner()
    {
        return !is_null($this->won_prize_id);
    }

    public function getColorName()
    {
        return __($this->color);
    }

    public function getColor()
    {
        $event = $this->userEvent->event;

        if ($event->isAvailable() && (strtotime($event->dt_end->toString()) <= strtotime(date('Y-m-d H:i', strtotime("+1 day"))))) {
            return "activenow";
        }

        if (!$event->isAvailable()) {
            if ($this->isWinner()) {
                return "winner";
            }

            return "expired";
        }


        return "active";
    }

}
