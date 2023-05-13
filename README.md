# GSMART (GMF Sales Monitoring and Revenue Tracking)

Sebagai User CSSM, Ada tiga point penting yang saya inginkan

-   Dapat melihat semua data Mutasi pesawat baik itu A/C Delivery maupun A/C Redelivery dalam satu table yang dapat difilter berdasarkan keinginan.
-   Dapat melaporkan kedatangan Aircraft di area hangar untuk melakukan maintenance. Sehingga, Tim Bonded dapat melakukan perhitungan perkiran untuk biaya PLB yang akan dibayarkan ketika Aircraft meninggalkan Area GMF
-   Dapat melporkan Aircraft Redelivery di area hangar yang sudah selesai melakukan maintenance. Sehingga, bisa memonitor aktivitas aircaft dan melakukan Updating Report pada Aircraft Mutation berupa file PDF

## Requirements

-   [Composser](https://getcomposer.org/download/)
-   [Node.js](https://nodejs.org/en/)
-   Laravel >= 9.x
-   PHP >= 8.0 - 8.1

## Installation

_Below is an example of how you can instruct your audience on installing and setting up your app. This template doesn't rely on any external dependencies or services._

1. Clone Repository `git clone -b production https://bitbucket.gmf-aeroasia.co.id/scm/gdo/new-plb.git`
2. go to project folder. `cd new-plb`
3. Save as the. `env.example` to `.env` and set your database.
4. `composer install`
5. `npm install --save-dev`
6. Next, run the program key generation and commands migration

    ```sh
    php artisan migrate:fresh --seed
    php artisan passport:install
    php artisan key:generate
    php artisan storage:link
    php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
    npm run dev
    ```

7. Run the web server
   `php artisan serve`
8. After running the web server, open this address in your browser:
   `http://127.0.0.1:8000`

## Contact

Sigit Prayoga - sigit.prayoga@opencloud.id \
Fatur Ridlwan - fatur.ridlwan@opencloud.id\
Rizky Ibrahim - rizky.ibrahim@opencloud.id \
Alnuria Vanadia Equila - vanadia.equila@opencloud.id\
Galuh Idam Danutirto - galuh.danutirto@opencloud.id\
I Putu Sedana Wijaya - putu.wijaya@opencloud.id
