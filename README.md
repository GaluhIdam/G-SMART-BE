# GSMART (GMF Sales Monitoring and Revenue Tracking)

Memperbaharui G-Smart (Module Sales Plan) dikarenakan saat ini aplikasi
existing tidak user friendly dan tidak effisien secara waktu untuk digunakan (
proses loading dan uploading sangat lama).

## Requirements

-   [Composser](https://getcomposer.org/download/)
-   Laravel >= 9.x
-   PHP >= 8.0 - 8.1

## Installation

_Below is an example of how you can instruct your audience on installing and setting up your app. This template doesn't rely on any external dependencies or services._

1. Clone Repository `git clone -b dev https://github.com/GaluhIdam/G-SMART-BE.git`
2. go to project folder. `cd GSMART-BE`
3. Save as the. `env.example` to `.env` and set your database.
4. `composer install`
5. Next, run the program key generation and commands migration

    ```sh
    php artisan migrate:fresh --seed
    php artisan passport:install
    php artisan key:generate
    php artisan storage:link
    php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
    ```

6. Run the web server
   `php artisan serve`
7. After running the web server, open this address in your browser:
   `http://127.0.0.1:8000`

## Contact

Sigit Prayoga - sigit.prayoga@opencloud.id \
Fatur Ridlwan - fatur.ridlwan@opencloud.id\
Rizky Ibrahim - rizky.ibrahim@opencloud.id \
Alnuria Vanadia Equila - vanadia.equila@opencloud.id\
Galuh Idam Danutirto - galuh.danutirto@opencloud.id\
I Putu Sedana Wijaya - putu.wijaya@opencloud.id
