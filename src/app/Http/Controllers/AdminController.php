<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function actionUsers()
    {
        if (Auth::user()->isAdmin()) {
            $loadedUsers = User::get()->all();
            return view('admin.users', ['users' => $loadedUsers]);
        } else {
            return redirect('/');
        }
    }

    public function actionEvents()
    {
        if (Auth::user()->isAdmin()) {
            $events = array();
            $_events = Event::get()->all();
            foreach ($_events as $event) {
                $events[$event->id]['event'] = $event;
                $userEvents = UserEvent::where('event_id', $event->id)->get()->all();
                foreach ($userEvents as $userEvent) {
                    $tickets = Ticket::where('user_event_id', $userEvent->id)->get()->all();
                    foreach ($tickets as $ticket) {
                        $user = User::find($userEvent->user_id);

                        $events[$event->id]['tickets'][$user->email][] = $ticket;
                    }
                }
            }
            return view('admin.events', ['events' => $events]);
        } else {
            return redirect('/');
        }
    }
}
