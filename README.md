# Transport App

## Projekt áttekintés

Ez a projekt egy **Laravel alapú szállítmánykezelő alkalmazás**, amelyet egy interiju feladatként hoztam létre. A rendszer támogatja az **adminisztrátorok** és a **fuvarozók** szerepkörét, lehetővé téve a munkák létrehozását, hozzárendelését és státusz követését mind **webes felületen**, mind pedig **token alapú REST API-n** keresztül.

Az alkalmazás tartalmaz:

* Szerepkör alapú hitelesítés (Admin / Fuvarozó)
* Munka életciklus kezelése
* Biztonságos API hozzáférés tokenekkel
* PHPUnit feature tesztek a fő funkciókhoz

---

## Szerepkörök és funkciók

### Admin

* Webes felületen történő bejelentkezés
* Munkák létrehozása, frissítése, hozzárendelése és törlése
* Fuvarozók regisztrációjának jóváhagyása
* API token generálása és megtekintése
* Admin irányítópult elérése

### Fuvarozó

* Webes felületen történő bejelentkezés
* Hozzárendelt munkák megtekintése
* Munka státusz frissítése (assigned → in_progress → completed / failed)
* Regisztrációkor automatikusan API tokent kap
* Fuvarozói irányítópult elérése

---

## Telepítés

### Követelmények

* PHP 8.2+
* Composer
* SQLite / MySQL
* Node.js & NPM (Vite asset-ekhez)

### Telepítési lépések

```bash
composer install
npm install
npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Az alkalmazás elérhető a következő címen:

```
http://127.0.0.1:8000
```

---

## Hitelesítés

* **Webes hitelesítés** Laravel guard-okkal:

  * `auth:admin`
  * `auth:driver`

* **API hitelesítés** token alapú:

  * A token a request header-ben kerül küldésre: `token: <API_TOKEN>`
  * Admin token teljes hozzáférést biztosít minden munkához
  * Fuvarozó token csak a **saját munkáik státuszának frissítésére** használható

---

## Alapértelmezett bejelentkezési adatok 

* **Admin** :

  ```
  Email: admin@adminmail.com
  Jelszó: password123
  ```

* **Fuvarozó** :

  ```
  Email: k.janos@mail.com
  Jelszó: driverpass
  ```

---

## API végpontok

### Admin API

| Módszer | Végpont                           | Leírás               |
| ------- | --------------------------------- | -------------------- |
| POST    | `/api/jobs`                       | Új munka létrehozása |
| PATCH   | `/api/jobs/{jobId}`               | Munka frissítése     |
| POST    | `/api/drivers/{driverId}/confirm` | Fuvarozó jóváhagyása |

**Header-ek:**

```
token: ADMIN_API_TOKEN
Accept: application/json
```

---

### Fuvarozó API

| Módszer | Végpont                           | Leírás                   |
| ------- | --------------------------------- | ------------------------ |
| PATCH   | `/api/driver/jobs/{jobId}/status` | Munka státusz frissítése |

**Header-ek:**

```
token: DRIVER_API_TOKEN
Accept: application/json
```

---

## Tesztelés

A projekt tartalmaz **PHPUnit feature teszteket**, amelyek lefedik a legfontosabb funkciókat:

### Megvalósított tesztek

* Admin képes új munka létrehozására
* Admin képes meglévő munka frissítésére
* Admin képes részleges mezők frissítésére
* Érvénytelen státusz esetén validáció
* Fuvarozó képes saját munkájának státuszát frissíteni (API)
* Fuvarozó nem frissítheti más munkáját

A tesztek a következőket használják:

* `RefreshDatabase`
* Seederek a model factory-k helyett

### Tesztek futtatása

```bash
php artisan test
```

---

## Projekt struktúra

```
app/
 ├── Http/Controllers
 ├── Models
 ├── Providers
routes/
 ├── web.php
 ├── api.php
database/
 ├── migrations
 ├── seeders
tests/
 ├── Feature
```

---

---

## Licenc

Ez a projekt interijú projektként jött létre az [Avorado](https://avorado.io/) részére.

---

**Minden feladat követelménye teljesítve, tesztelve és ellenőrizve.**
