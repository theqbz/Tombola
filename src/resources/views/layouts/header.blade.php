<nav class="navbar navbar-expand-xl navbar-dark bg-dark border-bottom border-ticketto border-5 text-tlight">
    <div class="container-lg">
    <!--Ticketto branding-->
        <a class="navbar-brand" href="{{ url('/') }}">
                <span class="fw-bold text-ticketto">Ticketto</span>
        </a>
    <!--menu-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end algin-center" id="navbarSupportedContent">
            <ul class="navbar-nav">
            <!-- Authentication Links -->
                @guest
                    @include('layouts.navbar.navbar_public')
                @else
                    @include('layouts.navbar.navbar_registered')
                @endguest
            </ul>
        </div>
    </div>
</nav>
