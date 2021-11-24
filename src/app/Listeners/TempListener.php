<?php

namespace App\Listeners;

use App\Events\TemporaryRegistered;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\TempMail;

class TempListener
{

    /**
     * Handle the event.
     *
     * @param TempListener $event
     *
     * @return void
     */
    public function handle(TemporaryRegistered $event)
    {
            $details = ['title' => __('Login credentials')."-".config('app.name'), 'url' => URL::to('/') . '/dashboard/' . $event->user->dashboard_url];
            Mail::to($event->user->email)->send(new \App\Mail\TempMail($details));
    }
}
