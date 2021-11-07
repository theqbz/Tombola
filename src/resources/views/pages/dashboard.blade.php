@extends('layouts.app')

@section('content')

        <!--üdvözlő üzenet-->
        <div class="container">
            <p class="display-3 text-light">{{$user->first_name . " " . $user->last_name}}</p>
            <p class="lead text-light">A Ticketto rendszere üdvözöl!</p>
        </div>

        <!--dolgok gyors elérése-->
        <div class="container">
            <div class="row">

                <div id="tickets" class="col-sm-12 col-lg-4 p-3 bg-dark">
                    <p class="h2 p-1 text-light fw-bold">Szelvényeim</p>
                    <!--szelvény: lejárt, nyert-->
                    <div class="card m-1 bg-info text-white">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Nyertes játék szelvénye</p>
                                    <p class="card-text">2021. november 6. 18:00</p>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <p class="h1 card-title fw-bold text-lg-end">P12</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--szelvény: rövidesen-->
                    <div class="card m-1 bg-danger text-white">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Hamarosan esedékes játék szelvénye</p>
                                    <p class="card-text">2021. november 15.</p>
                                </div>
                                <div class="col-sm-12 col-lg-4 align-items-center">
                                    <p class="h1 card-title fw-bold text-lg-end">Z05</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--szelvény: később-->
                    <div class="card m-1 bg-success text-white">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Későbbi játék szelvénye</p>
                                    <p class="card-text">2022. január 16. 19:30</p>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <p class="h1 card-title fw-bold text-lg-end">05</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--szelvény: lejárt, nem nyert-->
                    <div class="card m-1 bg-light text-dark">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Lejárt játék nyeretlen szelvénye</p>
                                    <p class="card-text">2021. augusztus 20. 21:00</p>
                                </div>
                                <div class="col-sm-12 col-lg-4">
                                    <p class="h1 card-title fw-bold text-lg-end">S05</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="events" class="col-sm-12 col-lg-4 p-3 bg-dark">
                    <p class="h2 p-1 text-light fw-bold">Játékok</p>
                    <!--játék: rövidesen-->
                    <div class="card m-1 border border-5 border-danger bg-light text-dark">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Hamarosan esedékes játék</p>
                                    <p class="card-text">2021. november 15.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--játék: később-->
                    <div class="card m-1 border border-5 border-success bg-light text-dark">
                        <div class="card-body">
                            <div class="row g-0 justify-content-center align-items-center">
                                <div class="col-sm-12 col-lg-8">
                                    <p class="h5 card-title">Későbbi játék</p>
                                    <p class="card-text">2022. január 16. 19:30</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="prizes" class="col-sm-12 col-lg-4 p-3 bg-dark">
                    <p class="h2 p-1 text-light fw-bold">Nyereményeim</p>
                </div>

            </div>
        </div>

@endsection
<!-- Scripts -->
@stack('scripts')
