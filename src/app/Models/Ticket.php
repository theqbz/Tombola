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


    public function userEvent() {
        return $this->belongsTo(UserEvent::class);
    }

}
