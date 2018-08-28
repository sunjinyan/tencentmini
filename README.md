# tencentmini
tencentmini
# first of all.let's edit config/app.php，regiser aliaases and providers

    'aliases' => [
         .
         .
         .
         .
        'Tencentmini' => Ttmn\Tencentmini\Facades\Tencentmini::class
    ]
    
    'providers' => [
             .
             .
             .
             .
            Ttmn\Tencentmini\TencentminiServiceProvider::class,
    ]
# run this code
php artisan vendor:publish --provider="Ttmn\Tencentmini\TencentminiServiceProvider"
