<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'event_id',
        'prize_title',
        'prize_description',
        'prize_img_url',
        'prize_value',
        'winner_ticket_id'


    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getImageUrl()
    {
        return asset('uploads/events/'.$this->prize_img_url);
    }
}
