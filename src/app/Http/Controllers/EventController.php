<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicketGroup;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $events = Event::whereDate('dt_end', '>', date('Y-m-d H:i'))->where('is_public',1)->get();

        return view('event.index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $user = Auth::user();
        if ($user->canCreateEvent()) {
            return view('event.create');
        }else {
            return view('user.index',$user)->with(['error'=>'You need to fill all data to create Event!','user'=>$user]);
        }
    }

    protected function validator(array $data) {
        $todayDate = date("Y-m-d H:i");

        $validationRules = array('title'         => ['required', 'string', 'max:255'],
                                 'description'   => ['required', 'string', 'max:255'],
                                 'location'   => ['required', 'string', 'max:255'],
                                 'dt_start_full'      => ['required','date','max:255', 'after:' . $todayDate],
                                 'dt_end_full'        => ['required','date','max:255', 'after:dt_start_full'],
                                 'is_public'     => ['required', 'boolean', 'max:255'],
                                 'auto_ticket'     => ['required', 'boolean', 'max:255']
        );

        return Validator::make($data, $validationRules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $upladedFiles = array();

        if (!$request->input('update') && count($request->files)) {
            foreach ($request->files as $inputName =>$file) {

                //get file name with extenstion
                $fileNameWithExt = $file->getClientOriginalName();

                //get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

                //get extension
                $extension = $file->getClientOriginalExtension();

                //file to store
                $fileNameToStore = $this->sanitize_file_name($fileName) . '_' . time() . '.' . $extension;

                //upload to store
                $file->move(public_path('uploads/events'), $fileNameToStore);
                $itemIdx = explode('_',$inputName);
                $itemIdx = end($itemIdx);

                $upladedFiles[$itemIdx] = [
                    'title' => $request->input('prize_title_'.$itemIdx),
                    'description' => $request->input('prize_title_'.$itemIdx),
                    'image' => asset('uploads/events/'.$fileNameToStore),

                ];
            }
        } elseif(!$request->input('update')) {

            return back()->with($request->all())->withErrors(['prize' => __('At least one prize needed')]);
        }else {
            $matches  = array();
            foreach ($request->all() as $k => $item) {
                if (preg_match('/prize_item_\w+/', $k)) {
                    $itemIdx = explode('_',$k);
                    $matches[end($itemIdx)][$itemIdx[count($itemIdx)-2]]=$item;
                };
            }
            if(!empty($matches)) {
                $upladedFiles = $matches;
            }
            else {
                return back()->with($request->all())->withErrors(['prize' => __('At least one prize needed')]);
            }

        }
        $request->merge(['dt_start_full' => $request->input('dt_start') . ' ' . $request->input('dt_start_time'),'dt_end_full'=>$request->input('dt_end') . ' ' . $request->input('dt_end_time')]);
        if($this->validator($request->all())->fails()){
            $request->flash();
            return back()->withErrors($this->validator($request->all()))->with(['images'=>$upladedFiles]);
        }
        $event = Event::create(['title'     => $request->input('title'),
                       'description' => $request->input('description'),
                       'location' => $request->input('location'),
                       'dt_start'  => $request->input('dt_start_full'),
                       'dt_end'    => $request->input('dt_end_full'),
                       'is_public' => $request->input('is_public'),
                       'auto_ticket' => $request->input('auto_ticket')]);

        if($request->input('auto_ticket')) {
            //@TODO limit
            $eventTicketGroup = EventTicketGroup::create([
                'event_id'=>$event->id,
                'ticket_color'=>'default',
            ]);
            $eventTicketGroup->setRandomColor();
        }else {
            //@TODO color,number chose
        }

        foreach ($upladedFiles as $prize) {
            Prize::create([
                'event_id'=>$event->id,
                'prize_title'=> $prize['title'],
                'prize_description'=>$prize['description'],
                'prize_img_url' => $prize['image']
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    public function myEvents() {
        return "asd";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    private function sanitize_file_name( $filename ) {
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", '.', ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}");
        $filename = str_replace($special_chars, '', $filename);
        $filename = preg_replace('/[\s-]+/', '-', $filename);
        $filename = trim($filename, '.-_');
        return $filename;
    }
}
