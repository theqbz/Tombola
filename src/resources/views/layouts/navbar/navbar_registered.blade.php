<nav class="navbar navbar-expand-md navbar-dark bg-dark border-bottom border-warning border-5">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#tickets">Szelvényeim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#prizes">Nyereményeim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#joinevent">Csatlakozás</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('event.index')}}">Publikus játékok</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('event.myevents')}}">Játékaim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('event.create')}}">Új játék</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.index')}}">Profil/beállítások</a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link" href="{{route('logout')}}">Kijelentkezés</a>
                </li>
                <li class="nav-item ms-2 d-none d-md-inline">
                    <a class="btn btn-secondary" href="{{route('logout')}}">Kijelentkezés</a>
                </li>
            </ul>
</nav>
