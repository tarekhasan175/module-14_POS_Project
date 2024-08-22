# NexusNova Booking System (NBS)

## How to use it

1. Run git clone https://github.com/masumbillah21/NBS_Booking_System
2. Run cd NBS_Booking_System
3. Run `npm install`
4. Run `composer install`
5. Run `cp .env.example .env`
    - Create a database
    - In .env file add your database login credentials
    - Added your SMTP credentials in .env file to make email work
6. Add APP_NAME & APP_URL
7. Run `php artisan key:generate`
8. Run `php artisan storage:link`
9. Run `php artisan migrate:refresh --seed`
10. Run `php artisan serve`
11. Open new termial and run `npm run dev`

- Go to your site link. (For localhost: http://127.0.0.1:8000/)

## Technology Used

- Laravel 11
- VueJs with Inertia
- Tailwind css
