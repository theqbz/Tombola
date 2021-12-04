<div class="card">
    <div class="card-header">
        <div class="h2 lead">{{__("Access data")}}</div>
    </div>
    <div class="card-body text-center text-sm-start ">
        <!--
        <div class="h3 small fw-bold text-uppercase">{{__('QR code')}}</div>
        -->
        <img style="width: 150px" src="{{asset('qrcodes/users/'.$user->id.'.svg')}}" alt="">
        <div class="h3 small fw-bold text-uppercase mt-3">{{__('Profile ID')}}</div>
        <div class="ps-2 text-center text-sm-start text-dark bg-ticketto text-uppercase fs-4 fw-bold">
            {{$user->getAccessCode()}}
        </div>
    </div>
</div>
