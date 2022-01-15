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

You are all set. **HTTParty** 🥳 Read the API docs [here](https://documenter.getpostman.com/view/3034547/UVXjLGFM) to test the app.


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
