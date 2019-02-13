# content

this package is the core of user management based on laravel.

This package required laravel auth. 

#### Step by step to use :

1. run `php artisan make:auth`.
2. run `php artisan migrate`.
3. run `composer require webappid/laravel-user`.
4. run `php artisan migrate` one more time.
5. run `webappid:user:seed` to create default data. This seeder always insert new data only without wipe the old data.
6. run `php artisan db:seed --class='WebAppId\User\Seeds\AdminResetPasswordTableSeeder'` to reset admin use.
7. default root / admin email is root@noname.com

If you have any question about this package, please don't hesitate to drop me an email at dyan.galih@gmail.com

Thanks to everyone to help me build this package. 