### PromoCodes Case Study
xxxxx wants to give out promo codes worth x amount during events so people can get free rides to and from the event. The flaw with that is people can use the promo codes without going to the event.

### Solution
Built with 
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

### Set Up
Since this is a [laravel](https://laravel.com) app, the host machine
needs to satisfy the following requirements documented in the [laravel docs](https://laravel.com/docs).

Please find laravel setup guide [here](https://laravel.com/docs/8.x).
Once the host machine is set up, clone this project

`git clone https://github.com/mwakaambrose/promocodes.git`

Then you can run the following command to install the laravel dependencies.

`cd promocodes`

`cp .env.example .env`

`php artisan key:generate`

`composer install`,

Then create a database add the credentials to the .env file.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=promocode
DB_USERNAME=root
DB_PASSWORD=
```

Then run migration and seeder to create the database tables and test user.

`php artisan migrate:fresh --seed`

You are all set. **HTTParty** 🥳 


### Tests

These are the current test cases for the promo code system.

#### Test coordinates
- Mulawa mall 0.4121508,32.6414086 (origin)
- Kira Health Center 0.4004731,32.6413863 (event)
- Kira Roundabout 0.4010102,32.6394255 (destination)
- Promo Code Radius 1km

#### Automated tests
Tp run tests, you can use the following command:

`./vendor/bin/pest` or `php artisan test`


![alt text](tests.png)

### API docs
Postman documentation is provided to argument the documentation on this 
readme.
Here is the link to Postman documentation: 
https://documenter.getpostman.com/view/3034547/UVXjLGFM

#### Login
POST `/api/v1/login`

**Request body**
```json
{
    "username":"ambrose@theonehq.com",
    "password":"somesecretpassword"
}
```
**Success Response**
```json
{
  "status": 200,
  "message": "Success",
  "data": {
    "token": "4|2o8o4fyST7MPZ3Q1taJXeri8yg7WAzI6z7HDUn5X"
  }
}
```

#### Get All Promo Codes
GET `/api/v1/promo-codes/all`

**Authorization**: Bearer Token <token>

**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": [
        {
            "id": 1,
            "event_id": 1,
            "code": "8GZ2PJ",
            "amount": "5000.00",
            "expires_at": "2022-01-13 20:00:00",
            "is_active": 0,
            "radius": "2.00",
            "radius_unit": "km",
            "created_at": "2022-01-13T16:15:24.000000Z",
            "updated_at": "2022-01-13T16:15:24.000000Z"
        },
        ....
    ]
}
```

#### Get Active Promo Codes
GET `/api/v1/promo-codes/active`

**Authorization**: Bearer Token <token>

**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": [
        {
            "id": 1,
            "event_id": 1,
            "code": "8GZ2PJ",
            "amount": "5000.00",
            "expires_at": "2022-01-13 20:00:00",
            "is_active": 1,
            "radius": "2.00",
            "radius_unit": "km",
            "created_at": "2022-01-13T16:15:24.000000Z",
            "updated_at": "2022-01-13T16:15:24.000000Z"
        },
        ....
    ]
}
```

#### Create Event
POST `/api/v1/events`

**Authorization**: Bearer Token <token>

**Request body**
```json
{
    "name": "Niyee Launch",
    "latitude": "0.4004731",
    "longitude": "32.6413863",
    "starts_at": "2022-01-13 12:00:00",
    "ends_at": "2022-01-13 18:30:00"
}
```
**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": {
        "name": "Niyee Launch",
        "latitude": "26.687412",
        "longitude": "‑80.681831",
        "starts_at": "2022-01-13 12:00:00",
        "ends_at": "2022-01-13 18:30:00",
        "updated_at": "2022-01-13T15:53:55.000000Z",
        "created_at": "2022-01-13T15:53:55.000000Z",
        "id": 1
    }
}
```

#### Create Promo Code
POST `/api/v1/promo-codes`

**Authorization**: Bearer Token <token>

**Request body**
```json
{
    "event_id": 1,
    "amount": 5000,
    "expires_at": "2022-01-13 20:00:00",
    "radius": "1",
    "radius_unit": "km"
}
```
**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": {
        "event_id": 1,
        "amount": 5000,
        "expires_at": "2022-01-13 20:00:00",
        "radius": "2",
        "radius_unit": "km",
        "code": "8GZ2PJ",
        "updated_at": "2022-01-13T16:15:24.000000Z",
        "created_at": "2022-01-13T16:15:24.000000Z",
        "id": 1
    }
}
```


#### Redeem Promo Code
Checks of the promo code is valid for the event.


POST `/api/v1/promo-codes/redeem`

**Authorization**: Bearer Token <token>

**Request body**
```json
{
    "code": "QSQGMH",
    "origin_latitude": "0.4121508",
    "origin_longitude": "32.6414086",
    "destination_latitude": "0.4010102",
    "destination_longitude": "32.6394255"
}
```
**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": {
        "promo_code": {
            "id": 1,
            "event_id": 1,
            "code": "QSQGMH",
            "amount": "5000.00",
            "expires_at": "2022-01-16 20:00:00",
            "is_active": 1,
            "radius": "2.00",
            "radius_unit": "km",
            "created_at": "2022-01-13 16:15:24",
            "updated_at": "2022-01-16 07:39:16"
        },
        "polyline": {
            "points": [
                "0.4121508,32.6414086",
                "0.4010102,32.6394255"
            ]
        }
    }
}
```


#### Deactivate Promo Code

PUT `/api/v1/promo-codes/1`

**Authorization**: Bearer Token <token>

**Request body**
```json
{
    "activate": false
}
```
**Success Response**
```json
{
    "status": 200,
    "message": "Success",
    "data": {
        "id": 1,
        "event_id": 1,
        "code": "QSQGMH",
        "amount": "5000.00",
        "expires_at": "2022-01-13 20:00:00",
        "is_active": 0,
        "radius": "2.00",
        "radius_unit": "km",
        "created_at": "2022-01-13T16:15:24.000000Z",
        "updated_at": "2022-01-16T07:39:16.000000Z"
    }
}
```




