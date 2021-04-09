# Tombola
###### Csoportfeladat Projektmunka 1. (GKNB_INTM004) tárgyból.<br/>Készítik: Borsodi Zoltán (B7PK8Z), Eszenyi Ákos Zsolt (OJY3FB), Tenk Norbert (DZYKCM)<br/>mérnökinformatika BSC, Széchenyi István Egyetem

> ## Feladat kiírása
> Tombolasorsolás szervező webalkalmazás készítése. A szervezők tudjanak sorsolást hirdetni, nyereményeket hozzárendelni, tombolákat kiosztani a résztvevőknek, nyerteseket sorsolni, átadás állapotát adminisztrálni, stb. A résztvevők lássák, hogy mennyi tombolájuk van, összesen hány tombola vesz részt a sorsoláson, kapjanak értesítést a nyereményükről, stb. További követelmények félév közben, iteratív fejlesztési folyamat során.

## 1. Mit tud?
Az alkalmazás egy általánosan felhasználható tombolajáték-szervező és lebonyolító platform, ahol a jákét *szervezői* létre tudnak hozni *tombolasorsolási alkalmakat*, amelyekre a *játékosok* benevezhetnek. A motor lebonyolítja a sorsolást, értesíti a nyerteseket és többek között figyelmezteti a szervezőket az esetleges teendőikre. (Pl. regisztrálni, hogy átvették-e a nyereményt.)

A tombolát szervezhetik nonprofit céllal (pl. iskolák) vagy marketing céllal (cégek nyereményjáték lebonyolítására). Az *alkalmakra* a játékosok jelentkezhetnek vagy meghívhatja őket az *alkalom* a szervezője. Ezen kívül a játékosoknak lehetősége van a platformon a nyilvánosnak meghirdetett sorsolások között tallózni és azokon résztvenni.

## 2. Hogyan működik?
### Alkalmak
Egy *tombolasorsolási alkalom* létrehozásakor meg kell adni:
- a sorsolás típusát
  - "klasszikus" (Meghatározott számú tombolaszelvény van a játékban, amelyek közül szabadon választhatnak a játékosok, mint a moziban az ülőhelyek esetében.)
  - "végtelen" (Annyi szelvény van az adott *alkalomhoz* rendelve, ahányat a játékosok vásárolnak - ebben az esetben nincs konkrét szelvény és választási lehetőség, csak a szelvényeket reprezentáló kódok, amelyeket a rendszer kioszt a szelvényt vásárló játékosoknak.)
  - "beküldött" (A játékba kerülő szelvények ezesetben a valós életbeli aktivitást igazoló azonosítók - pl. nyugta sorszáma. Ezeket a leendő játékos szerzi meg, amivel kedve szerint regisztrálhat a meghirdetett játékra.)
- időpontját
- nyilvánosságát
  - nyilvános (Az *alkalom* látható a tombola-platformon regisztráltak számára, akik szabadon jelentkezhetnek a játékra.)
  - meghívásos (Ebbe tartozik az összes nem nyilvános *alkalom*, amelyhez csak a szervezők meghívására lehet csatlakozni. Technikailag ide tartozik az is, amikor pl. egy nyugta sorszámával regisztrál valaki. Ebben az esetben a szervező meghívója a nyugtára kinyomtatott kód. De ide tartozhat egy cég csapatépítőjén rendezett és a platform segítségével lebonyolított tombolajáték is.)
- a nyereményeket
- a részvétel feltételét (pl. tombolaszelvény árának kifizetése)

Minden *alkalom* rendelkezik a megadott paraméterek alapján automatikusan generált címoldallal, amelyen keresztül a játékosok regisztrálhatnak.

### Játékosok
A játékosok többféle státuszban lehetnek a rendszerben:
- aktív (Adatai megadásával létrehozott egy felhasználói fiókot a platformon, amelynek segítségével több játékban - legyen az *nyilvános* vagy *meghívásos* - is azonos adatokkal vehet részt. Ha szeret játszani, ezzel elkerülheti, hogy újra és újra meg kelljen adni az adatait.)
- passzív ("Kívülről" egy, a platformot használó külső játékszervezőn keresztül érkező játékos. Ő nem rendelkezik felhasználói fiókkal, részvétele egy adott *alkalomhoz* van kötve. Egy konkrét példa az ilyen típusú felhasználóra: ha a Spar tombolát hirdet a SuperShop kártyát használók között, amelyre a SuperShop bizonylatra nyomtatott egyedi QR kóddal jelentkezhet a vásárló. Ha a vásárló a QR kódot leolvasva kifejezi részvételi szándékát, akkor a Spar által küldött adatokkal bekerül a platform adatbázisába. Az ilyen játékosoknak a rendszer email-ben ajánlja fel az aktív tagságot.)
- lehetséges


nak a játékohz szükséges személyes adatainak megadásával (név, email, postacím) regisztrálnia kell az platformra, így vehet részt a tombolajátékban.

- Klasszikus játék esetén választhat a rendelkezésre álló tombolákból
- Végtelen szelvény esetén a rendszer generál neki annyi szelvényt, ahány tombolát vásárolt
- Beküldött szelvény esetén a játékos adja meg azt az egyedi azonosítót (pl. vásárlást igazoló nyugta sorszámát) ami a tombolaszelvényét jelképezi

A nyeremények és a tombolaszelvények csak a sorsoláskor kapcsolódnak össze, tehát a nyereményhez "húz" a rendszer szelvényt. A nyertesek értesítést kapnak.
