@extends('layouts.app')

@section('content')
<div class="container-lg">
    <div class="row">
        <div class="col-5">
            <img src="assets/logo.svg" alt="Ticketto logo">
        </div>
        <div class="col-7 align-middle">
            <p class="lead">Üdvözlünk a Ticketto-n!<br>
            Ez Magyarország első és egyetlen félig-megddig már működő tombolajáték szervező és lebonyolító platformja.
            A kezdéshez kattints a Bejelentkezésre!
            </p>
        </div>
    </div>
    <div class="row mt-5">
        <div class="accordion" id="sugo">
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesEgy">
                    <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#miaticketto"
                    aria-expanded="true" aria-controls="miaticketto">
                        Mi ez a Ticketto?
                    </button>
                </h2>
                <div id="miaticketto" class="accordion-collapse collapse show"
                aria-labelledby="kerdesEgy" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        A Ticketto egy online tombolajáték szervező és lebonyolító platform, amelynek segítségével bárki indíthat tombolajátékot (eseményt) vagy játékosként részt vehet a tombolasorsolásokon. A rendszer a megadott időben kisorsolja az eseményhez tartozó nyereményeket és értesíti a nyertes szelvények tulajdonosát.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesKetto">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#reszvetel"
                    aria-expanded="false" aria-controls="reszvetel">
                        Hogyan vehetek részt a játékokban?
                    </button>
                </h2>
                <div id="reszvetel" class="accordion-collapse collapse" aria-labelledby="kerdesKetto" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        Az adott játék beállításaitól függően kétféle lehetőség van: az egyik eset, hogy a szervező osztja a tombolákat a résztvevőknek. A másik, hogy a játék főoldalán csatlakozhatsz az eseményhez - ilyenkor automatikusan kapsz egy tombolaszelvényt.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesHarom">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#regisztracio"
                    aria-expanded="false" aria-controls="regisztracio">
                        Szükség van regisztrációra?
                    </button>
                </h2>
                <div id="regisztracio" class="accordion-collapse collapse" aria-labelledby="kerdesHarom" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        <p>Játékosként akkor is részt vehetsz a meghirdetett játékokon, ha csupán emiatt még nem szeretnéd megadni az adataidat. Ebben az esetben ideiglenes felhasználó lehetsz, amihez elég csak egy email címet megadnod. Erre azért van szükség, hogy a Ticketto rendszere kommunikálhasson veled. Az ideiglenes felhasználók – akárcsak a regisztrált játékosok – kaphatnak tombolákat és természetesen nyerhetnek is velük. Ideiglenes felhasználóként az email-ben kapott linkkel tudsz belépni az oldalra, ezen kívül a rendszer csak akkor küld üzenetet, ha nyertél.</p>
                        <p>Javasoljuk ugyanakkor, hogy legyél regisztrált felhasználó, hogy a Ticketto minden funkciójához hozzáférj. Ehhez az email címeden kívül meg kell adnod a nevedet és be kell állítanod egy jelszót is.</p>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesNegy">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#jatekinditasa"
                    aria-expanded="false" aria-controls="jatekinditasa">
                        Hogyan tudok játékot indítani?
                    </button>
                </h2>
                <div id="jatekinditasa" class="accordion-collapse collapse" aria-labelledby="kerdesNegy" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        <p>Új játékot csak regisztrált felhasználók indíthatnak. Játék indításakor el kell döntened, hogy privát vagy publikus legyen-e az esemény és azt, hogy a leendő játékosok kedvükre választhassanak-e a tombolák közül színek és számok alapján vagy a rendszer automatikusan osszon nekik annyit ahányat kérnek.</p>
                        <p>Fontos a játék befejezésének idejét megadni. Ez lesz a sorsolás időpontja, amikor a rendszer kisorsolja a nyereményeket és értesíti a nyertes tombolaszelvények tulajdonosait.</p>
                        <p>A játékhoz szükség van nyereményre is. A nyereményeket a játék létrehozásakor kell hozzáadni az eseményhez. Egy eseményhez több nyeremény is tartozhat.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesOt">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#publikus"
                    aria-expanded="false" aria-controls="publikus">
                        Privát vagy publikus játék?
                    </button>
                </h2>
                <div id="publikus" class="accordion-collapse collapse" aria-labelledby="kerdesOt" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        <p>A publikus játékok minden regisztrált felhasználó számára láthatóvá válnak az oldal Publikus játékok menüpontjában. A privát eseményekről viszont a szervező értesítheti a leendő játékosait, ezek ugyanis nem jelennek meg a publikus játékok között.</p>
                        <p>Például, ha egy baráti társaság kvíz-estet vagy szilveszteri bulit szervez magának, amiben valamilyen módon tombolasorolás is lesz, akkor célszerű privátnak beállítani az eseményt. Ilyen esetben a összejövetel helyszínén a szervező tudatja az ismerőseivel, hogy hogyan csatlakozhatnak a játékhoz. Lehet, hogy a szervező saját maga osztja a tombolákat de akár ki is nyomtathatja a játék adatlapját amiről a résztvevők leolvashatják az esemény oldalára vezető QR-kódot a telefonjaikkal.</p>
                        <p>Publikus eseményt akkor érdemes indítani, ha a szervező nem tudja előre kik, milyen körülmények között csatlakoznak a játékhoz, például marketing céllal indított játékok esetében.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesHat">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#szines"
                    aria-expanded="false" aria-controls="szines">
                        Színes vagy nem színes tombolák?
                    </button>
                </h2>
                <div id="szines" class="accordion-collapse collapse" aria-labelledby="kerdesHat" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        <p>Az emberek szeretnek válogatni a tombolaszelvények között kedvenc színük vagy szerencseszámaik alapján. Ha szervezőként egy eseményen szeretnéd megadni ezt a választási lehetőséget a játékosoknak, akkor a létrehozáskor válaszd a színes tombolák opciót.</p>
                        <p>Ugyanakkor nem mindig van igény a válogatásra vagy esetleg a körülmények miatt egyszerűen nem célszerű ezt a lehetőséget biztosítani. Ehhez az eseményt a színes tombolák opció kikapcsolásával kell létrehozni, mert ebben az esetben a rendszer kérdés nélkül, emelkedő sorrendben osztja a tombolákat a játékosoknak.</p>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="kerdesHat">
                    <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#jog"
                    aria-expanded="false" aria-controls="jog">
                        Jogi nyilatkozat
                    </button>
                </h2>
                <div id="jog" class="accordion-collapse collapse" aria-labelledby="kerdesHat" data-bs-parent="#sugo">
                    <div class="accordion-body">
                        <p>A Ticketto rendszerében tárolt adatokat kapcsolattartás céljából kezeljük, azokat harmadik félnek nem adjuk ki és a felhasználó kérésére töröljük.</p>
                        <p>A Ticketto egy béta-teszt fázisban lévő rendszer. Használói a használattal elfogadják az esetleges adatvesztés kockázatát és maguk viselik az ebből fakadó következményeket. A játékok, események szervezői maguk felelnek a játékok, események biztonságos lebonyolításáért, a nyereményekért és azért, hogy a nyereményeket a nyertesek megkapják.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
