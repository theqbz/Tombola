# Tombola
Tombolasorsolás szervező webalkalmazás készítése

## Feladat kiírása
A szervezők tudjanak sorsolást hirdetni, nyereményeket hozzárendelni, tombolákat kiosztani a résztvevőknek, nyerteseket sorsolni, átadás állapotát adminisztrálni, stb. A résztvevők lássák, hogy mennyi tombolájuk van, összesen hány tombola vesz részt a sorsoláson, kapjanak értesítést a nyereményükről, stb. További követelmények félév közben, iteratív fejlesztési folyamat során.

## 1. Mit tud?
Az alkalmazás egy általános tombolajáték-szervező platform, ahol a jákét *szervezői* létre tudnak hozni *tombolasorsolási alkalmakat*, amelyekre pedig a *játékosok* benevezhetnek.

A tombolát szervezhetik nonprofit céllal (pl. iskolák) vagy marketing céllal (cégek nyereményjáték lebonyolítására). A tombolára a szervező meghívhat játékosokat, de a platformon regisztrált játékosok saját maguk is jelentkezhetnek a nyilvánosnak meghirdetett sorsolásokra.

Egy *tombolasorsolási alkalom* létrehozásakor meg kell adni:
- a sorsolás típusát
  - "klasszikus" meghatározott számú szelvénnyel
  - "végtelen" szelvénnyel
  - "beküldött szelvénnyel" - pl. a blokk sorszáma
- időpontját
- nyilvánosságát
  - nyilvános
  - meghívásos
- a nyereményeket
- a részvétel feltételét (pl. tombolaszelvény árának kifizetése vagy pl. IRL vásárlást igazoló blokk sorszáma stb. - ehhez adott esetben kell egy nyilvános API, amin keresztül a szervező a saját rendszerének segítségével ellenőrizheti a részvételi feltételeket, pl. a blokk sorszámát)

Minden *alkalom* rendelkezik a megadott paraméterek alapján automatikusan generált címoldallal, amelyen keresztül a játékosok regisztrálhatnak.

A játékosnak bizonyos személyes adatainak megadásával (név, email, postacím) regisztrálnia kell az platformra, így vehet részt a tombolajátékban.

- Klasszikus játék esetén választhat a rendelkezésre álló tombolákból
- Végtelen szelvény esetén a rendszer generál neki annyi szelvényt, ahány tombolát vásárolt
- Beküldött szelvény esetén a játékos adja meg azt az egyedi azonosítót (pl. vásárlást igazoló nyugta sorszámát) ami a tombolaszelvényét jelképezi

A nyeremények és a tombolaszelvények csak a sorsoláskor kapcsolódnak össze, tehát a nyereményhez "húz" a rendszer szelvényt. A nyertesek értesítést kapnak.
