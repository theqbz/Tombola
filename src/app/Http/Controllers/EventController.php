<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicketGroup;
use App\Models\Prize;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\File\Exception\IniSizeFileException;

class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::whereDate('dt_end', '>', date('Y-m-d H:i'))->where('is_public', 1)->get();

        return view('event.index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->canCreateEvent()) {
            return view('event.create')->with(['colors' => EventTicketGroup::getColors()]);
        } else {
            return view('user.index', $user)->with(['error' => __('You need to fill all data to create Event!'),
                'user' => $user]);
        }
    }

    protected function validator(array $data, $isUpdate = false)
    {
        $todayDate = date("Y-m-d H:i");

        $validationRules = array('title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'dt_start_full' => ['required', 'date', 'max:255', 'after:' . $todayDate],
            'dt_end_full' => ['required', 'date', 'max:255', 'after:dt_start_full'],
        );

        if (!$isUpdate) {
            $validationRules = array_merge($validationRules, array(
                'limit' => ['required', 'numeric', 'max:100'],
                'is_public' => ['required', 'boolean', 'max:255'],
                'auto_ticket' => ['required', 'boolean', 'max:255']
            ));
        }

        return Validator::make($data, $validationRules);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        $upladedFiles = array();
        $request->merge(['dt_start_full' => $request->input('dt_start') . ' ' . $request->input('dt_start_time'),
            'dt_end_full' => $request->input('dt_end') . ' ' . $request->input('dt_end_time')]);

        $matches = array();
        foreach ($request->all() as $k => $item) {
            if (preg_match('/prize_item_\w+/', $k)) {
                $itemIdx = explode('_', $k);
                $matches[end($itemIdx)][$itemIdx[count($itemIdx) - 2]] = $item;
            }
        }

        if (count($request->files) || !empty($matches)) {
            foreach ($request->files as $inputName => $file) {
                //get file name with extenstion
                $fileNameWithExt = $file->getClientOriginalName();

                //get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

                //get extension
                $extension = $file->getClientOriginalExtension();

                //file to store
                $fileNameToStore = $this->sanitize_file_name($fileName) . '_' . time() . '.' . $extension;

                //upload to store
                $path = public_path('temp/events');
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true, true);
                }
                try {
                    $file->move($path, $fileNameToStore);
                } catch (IniSizeFileException $ex) {
                    $request->flash();
                    return back()->withErrors(['prize' => $ex->getMessage()]);
                }

                $itemIdx = explode('_', $inputName);
                $itemIdx = end($itemIdx);
                $upladedFiles[$itemIdx] = ['title' => $request->input('prize_title_' . $itemIdx),
                    'description' => $request->input('prize_description_' . $itemIdx),
                    'image' => $fileNameToStore

                ];
            }
        } else {
            $errors = array_merge($this->validator($request->all())->errors()
                ->messages(), ['prize' => __('At least one prize needed')]);
            $request->flash();
            return back()->with($request->all())->withErrors($errors)->with(['images' => $upladedFiles]);
        }

        if (!empty($matches)) {
            $upladedFiles = array_merge($upladedFiles, $matches);
        }


        if ($this->validator($request->all())->fails()) {
            $request->flash();
            return back()->withErrors($this->validator($request->all()))->with(['images' => $upladedFiles]);
        }
        //Create Event
        $event = Event::create(['title' => $request->input('title'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'dt_start' => $request->input('dt_start_full'),
            'dt_end' => $request->input('dt_end_full'),
            'is_public' => $request->input('is_public')]);

        $this->generateAccesses($event);

        if ($event->is_public && $request->input('auto_ticket')) {
            $eventTicketGroup = EventTicketGroup::create(['event_id' => $event->id, 'limit' => $request->input('limit'), 'ticket_color' => 'default']);
            $eventTicketGroup->setRandomColor();
        } else {

            $event->auto_ticket = $request->input('auto_ticket');
            $event->save();
            if ($request->input('chosable_color')) {
                foreach ($request->input('colors') as $color) {
                    EventTicketGroup::create(['event_id' => $event->id, 'limit' => $request->input('limit'), 'ticket_color' => $color]);
                }
            } else {
                $eventTicketGroup = EventTicketGroup::create(['event_id' => $event->id, 'limit' => $request->input('limit'), 'ticket_color' => 'default',]);
                $eventTicketGroup->setRandomColor();
            }
        }
        if (!File::exists(public_path('uploads/events'))) {
            File::makeDirectory(public_path('uploads/events'), 0755, true, true);
        }

        foreach ($upladedFiles as $prize) {
            File::move(public_path('temp/events') . "/" . $prize['image'], public_path('uploads/events') . "/" . $prize['image']);

            Prize::create(['event_id' => $event->id, 'prize_title' => $prize['title'],
                'prize_description' => $prize['description'], 'prize_img_url' => $prize['image']]);
        }

        UserEvent::create(['user_id' => Auth::user()->id, 'event_id' => $event->id, 'access_type' => 1]);

        return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        $event = Event::find($id);
        return view('event.show', ['event' => $event]);
    }

    /**
     * Display the specified resource by hash.
     *
     * @param string $hash
     *
     * @return \Illuminate\Http\Response
     */
    public function showByHash(Request $request)
    {
        $hash = $request->route('hash');
        $event = Event::where('hash', $hash)->first();
        if ($event) {
            return view('event.public')->with('event', $event);
        } else {
            return abort(404);
        }
    }

    public function redirectByHash(Request $request)
    {
        $hash = $request->input('hash');
        $hash = explode('/', $hash);
        $hash = end($hash);
        if ($hash) {
            $event = Event::where('hash', $hash)->first();
            if ($event) {
                return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);
            } else {
                return back()->withErrors(['error' => _('No event found')]);
            }
        } else {
            return back()->withErrors(['error' => _('No acces code')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
    {
        $event = Event::find($id);

        if (Auth::user()->isEditor($id)) {
            return view('event.edit', ['event' => $event]);
        }

        return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        $request->merge(['dt_start_full' => $request->input('dt_start') . ' ' . $request->input('dt_start_time'),
            'dt_end_full' => $request->input('dt_end') . ' ' . $request->input('dt_end_time')]);
        $this->validator($request->all(), true)->validate();
        try {
            $event = Event::find($id);
            $event->update(['title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'dt_start' => $request->input('dt_start_full'),
                'dt_end' => $request->input('dt_end_full')]);
            $event->save();
        } catch (Exception $e) {
            return back()->with(['error' => __('Failed Save')]);
        }


        return back()->with(['success' => __('Saved')]);
    }

    public
    function myEvents()
    {
        $events = Auth::user()->getOwnEvents();
        $passiveEvents = Auth::user()->getOwnEvents('passive');
        return view('event.myevents', ['events' => $events, 'passiveEvents' => $passiveEvents]);
    }

    public
    function connectToEvent()
    {
        return view('event.connect');
    }

    public
    function showTicketForm($id)
    {
        $event = Event::find($id);

        if (Auth::user()->isEditor($id)) {
            return view('event.ticketForm', ['event' => $event]);
        }

        return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);


    }

    public
    function getEventAutoTicket($event)
    {
        $args = array();
        $eventTicketGroup = $event->eventTicketGroups->first();
        $limit = $eventTicketGroup->limit;
        $color = $eventTicketGroup->ticket_color;
        $args['color'] = $color;
        $args['value'] = $this->nextAutoTicket($event, $color, $limit);
        if (is_null($args['value'])) {
            return null;
        }
        return $args;
    }

    public
    function checkUnlimitedTicket($event, $number, $color = null)
    {
        $eventTicketGroup = $event->eventTicketGroups->first();
        $limit = $eventTicketGroup->limit;
        if ($limit === 0) {
            $tickets = array();
            $userEvents = $event->userEvents->all();
            foreach ($userEvents as $userEvent) {
                if (!is_null($color)) {
                    $tickets = array_merge($tickets, $userEvent->tickets->where('color', $color)->pluck('value')->all());
                } else {
                    $tickets = array_merge($tickets, $userEvent->tickets->pluck('value')->all());
                }
            }
            return !array_search($number, $tickets);
        }

        return true;
    }

    public
    function nextAutoTicket($event, $color, $limit)
    {
        $tickets = array();
        $userEvents = $event->userEvents->all();
        foreach ($userEvents as $userEvent) {
            $tickets = array_merge($tickets, $userEvent->tickets->where('color', $color)->all());
        }
        $lastValue = end($tickets) ? end($tickets)->value + 1 : 1;
        if ($limit != 0 && $lastValue > $limit) {
            return null;
        } else {
            return $lastValue;
        }

    }

    public
    function showTicketColorForm(Request $request)
    {
        $event = Event::find($request->input('id'));

        $user = User::where('hash', $request->input('hash'))->first();

        if (!$user || !$event) {
            return redirect()->route('event.ticket', ['id' => $event->id])->withErrors(['error' => __('Error happened!Please try again!')]);
        }
        if ($user->isEditor($event->id)) {
            return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);
        } else {
            $colors = $event->getAvailableColors();
            return view('event.ticketColor')->with(['event' => $event, 'uid' => $user->id, 'colors' => $colors]);
        }
    }

    public
    function addTicketColor(Request $request)
    {
        $event = Event::find($request->input('id'));

        $user = User::find($request->input('uid'));
        if (!$user || !$event) {
            return redirect()->route('event.ticket', ['id' => $event->id])->withErrors(['error' => __('Error happened!Please try again!')]);
        }

        if ($user->isEditor($event->id)) {
            return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);
        } else {

            if ($color = $request->input('color')) {
                $tickets = $event->getAvailableTickets($color);
                return view('event.ticketNumber')->with(['event' => $event, 'color' => $color, 'tickets' => $tickets, 'uid' => $user->id]);
            } else {
                $tickets = $event->getAvailableTickets();
                return view('event.ticketNumber')->with(['event' => $event, 'tickets' => $tickets, 'uid' => $user->id]);
            }
        }
    }

    public
    function showTicketNumberForm(Request $request)
    {

        $event = Event::find($request->input('id'));

        $user = User::where('hash', $request->input('hash'))->first();

        if (!$user || !$event) {
            return redirect()->route('event.ticket', ['id' => $event->id])->withErrors(['error' => __('Error happened!Please try again!')]);
        }

        $tickets = $event->getAvailableTickets();

        return view('event.ticketNumber')->with(['event' => $event, 'tickets' => $tickets, 'uid' => $user->id]);


    }

    public
    function addTicket(Request $request)
    {

        $event = Event::find($request->input('id'));
        if ($request->input('uid')) {
            $user = User::find($request->input('uid'));
        } else {
            $user = User::where('hash', $request->input('hash'))->first();
        }
        if (!$user) {
            return redirect()->route('event.ticket', ['id' => $event->id])->withErrors(['error' => __('Error happened!Please try again!')]);
        }
        if (!$user->isEditor($event->id)) {

            if ($event->auto_ticket) {
                $args = $this->getEventAutoTicket($event);
            } else {
                $number = $request->input('number');
                $color = null;
                if ($request->input('color')) {
                    $color = $request->input('color');
                    $args['color'] = $color;
                } else {
                    $eventGroup = $event->eventTicketGroups->first();
                    $args['color'] = $eventGroup->ticket_color;
                }
                if ($this->checkUnlimitedTicket($event, $number, $color)) {
                    $args['value'] = $number;
                } else {
                    $args = null;
                }


            }

            if (is_null($args)) {
                return redirect()->route('event.ticket', ['id' => $event->id])->withErrors(['error' => __('Error happened!Please try again!')]);
            }

            $userEvent = UserEvent::where(['user_id' => $user->id, 'event_id' => $event->id])->first();
            if (!$userEvent) {
                $userEvent = UserEvent::create(['user_id' => $user->id, 'event_id' => $event->id, 'access_type' => 0]);
            }
            $args['user_event_id'] = $userEvent->id;
            Ticket::create($args);

            if (Auth::user()->isEditor($event->id)) {
                return redirect()->route('event.ticket', ['id' => $event->id])->with('success', __('Ticket successfully added!'));
            } else {
                return redirect()->route('mytickets');
            }

        } else {
            return back()->withErrors(['error' => __('Editors can\'t get tickets')]);
        }

    }


    private
    function sanitize_file_name($filename)
    {
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", '.', ":", ";", ",", "'", "\"", "&", "$", "#",
            "*", "(", ")", "|", "~", "`", "!", "{", "}");
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');

        return $filename;
    }

    function replaceAccents($str)
    {
        return utf8_decode(strtr(utf8_decode($str), utf8_decode('áéóöőűűíÁÉÓÖŐÜŰÍ'), 'aeooouuiAEOOOUUI'));
    }

    private
    function generateAccesses(&$event)
    {

        $accessCode = "U-" . strtolower(substr($event->title, 0, 3) . substr(time(), -3));
        $event->hash = $accessCode;
        $event->save();
        $path = public_path('/qrcodes/events');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        QrCode::generate(URL::to('/event/landing') . "/" . $accessCode, $path . '/' . $event->id . '.svg');
    }
}
