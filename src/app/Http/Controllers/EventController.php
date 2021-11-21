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
            return view('event.create');
        } else {
            return view('user.index', $user)->with(['error' => __('You need to fill all data to create Event!'),
                'user' => $user]);
        }
    }

    protected function validator(array $data)
    {
        $todayDate = date("Y-m-d H:i");

        $validationRules = array('title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'dt_start_full' => ['required', 'date', 'max:255', 'after:' . $todayDate],
            'dt_end_full' => ['required', 'date', 'max:255', 'after:dt_start_full'],
            'is_public' => ['required', 'boolean', 'max:255'],
            'auto_ticket' => ['required', 'boolean', 'max:255']);

        return Validator::make($data, $validationRules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                    'description' => $request->input('prize_title_' . $itemIdx),
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
            'is_public' => $request->input('is_public'),
            'auto_ticket' => $request->input('auto_ticket')]);

        $this->generateAccesses($event);

        if ($request->input('auto_ticket')) {
            //@TODO limit
            $eventTicketGroup = EventTicketGroup::create(['event_id' => $event->id, 'ticket_color' => 'default',]);
            $eventTicketGroup->setRandomColor();
        } else {
            //@TODO color,number chose
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
    public function show($id)
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
            return view('event.show')->with('event', $event);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        $request->merge(['dt_start_full' => $request->input('dt_start') . ' ' . $request->input('dt_start_time'),
            'dt_end_full' => $request->input('dt_end') . ' ' . $request->input('dt_end_time')]);
        $this->validator($request->all())->validate();
        try {
            $event = Event::find($id);
            $event->update(['title' => $request->input('title'),
                'description' => $request->input('description'),
                'location' => $request->input('location'),
                'dt_start' => $request->input('dt_start_full'),
                'dt_end' => $request->input('dt_end_full'),
                'is_public' => $request->input('is_public'),
                'auto_ticket' => $request->input('auto_ticket')]);
        } catch (Exception $e) {
            return back()->with(['error' => __('Failed Save')]);
        }


        return back()->with(['success' => __('Saved')]);
    }

    public function myEvents()
    {
        $events = Auth::user()->getOwnEvents();

        return view('event.myevents', ['events' => $events]);
    }
    public function myTickets(Request $request)
    {
        $status = (!is_null($request->input('status')))?$request->input('status'):'active';
        $eventTickets = Auth::user()->listTickets($status);

        return view('user.tickets', ['eventTickets' => $eventTickets]);
    }

    public function showTicketForm($id)
    {
        $event = Event::find($id);

        if (Auth::user()->isEditor($id)) {
            return view('event.ticketForm', ['event' => $event]);
        }

        return redirect()->route('event.show', ['id' => $event->id])->with(['event' => $event]);


    }

    public function checkEventTicket($event)
    {
        $args = array();

        if ($event->auto_ticket) {
            $eventTicketGroup = $event->eventTicketGroups->first();
            $limit = $eventTicketGroup->limit;
            $color = $eventTicketGroup->ticket_color;
            $args['color'] = $color;
            $args['value'] = $this->nextTicket($event, $color, $limit);
            if (is_null($args['value'])) {
                return null;
            }
            return $args;
        }
    }

    public function nextTicket($event, $color, $limit)
    {
        $tickets = array();
        $userEvents = $event->userEvents->all();
        foreach ($userEvents as $userEvent) {
            $tickets = array_merge($tickets, $userEvent->tickets->where('color', $color)->all());
        }
        if ($limit != 0 && end($tickets)->value + 1 > $limit) {
            return null;
        } else {
            return end($tickets)['value'] + 1;
        }


    }

    public function availableTickets($color)
    {

    }

    public function addTicket(Request $request)
    {
        $event = Event::find($request->input('id'))->first();
        $user = User::where('hash', $request->input('hash'))->first();
        if(!$user) {
            return back()->withErrors(['error' => __('User not found!')]);
        }
        if (!$user->isEditor($event->id)) {
            $args = $this->checkEventTicket($event);
            if (is_null($args)) {
                return back()->withErrors(['error' => __('You reached the limit of tickets')]);
            }

            $userEvent = UserEvent::where(['user_id' => $user->id, 'event_id' => $event->id])->first();
            if (!$userEvent) {
                $userEvent = UserEvent::create(['user_id' => $user->id, 'event_id' => $event->id, 'access_type' => 0]);
            }
            $args['user_event_id'] = $userEvent->id;
            Ticket::create($args);
        }else {
            return back()->withErrors(['error' => __('Editors can\'t get tickets')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function sanitize_file_name($filename)
    {
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", '.', ":", ";", ",", "'", "\"", "&", "$", "#",
            "*", "(", ")", "|", "~", "`", "!", "{", "}");
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');

        return $filename;
    }

    private function generateAccesses(&$event)
    {

        $accessCode = "U-" . strtolower(substr($event->title, 0, 3) . substr(time(), 0, 3));
        $event->hash = $accessCode;
        $event->save();
        $path = public_path('/qrcodes/events');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        QrCode::generate(URL::to('/event/') . $accessCode, $path . '/' . $event->id . '.svg');
    }
}
