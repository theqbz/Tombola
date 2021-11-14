<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketGroup extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'ticket_color',
        'limit',
        'sold'
    ];

    private $colors = array('Piros','Fehér','Zöld','Sárga');

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function getColors() {
        return $this->colors;
    }

    public function setRandomColor() {
        $idx = rand(0,(count($this->colors)-1));
        $this->ticket_color = $this->colors[$idx];
        $this->save();
    }
}
