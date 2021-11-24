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

    const COLORS = array('red'=>'Piros','white'=>'Fehér','green'=>'Zöld','yellow'=>'Sárga');

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function getColors() {
        return self::COLORS;
    }

    public function setRandomColor() {
        $colorKeys = array();
        foreach (self::COLORS as $colorKey => $colorFull) {
            $colorKeys[] = $colorKey;
        }
        $idx = rand(0,(count($colorKeys)-1));
        $this->ticket_color = $colorKeys[$idx];
        $this->save();
    }

}
