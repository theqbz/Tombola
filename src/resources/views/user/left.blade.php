<div class="card">

    <div class="card-header">
        <h2>{{__("Access data")}}</h2>
    </div>
    <div class="card-body">
        <h3>{{__('Access code')}}</h3>
        <div class="badge badge-info p-3 pb-0 text-uppercase text-white" style="font-size: 1.5rem">{{$user->getAccessCode()}}</div>
        <hr>
        <h3>{{__('QR code')}}</h3>
        <img style="width: 150px" src="{{asset('qrcodes/users/'.$user->id.'.svg')}}" alt="">
    </div>
</div>
