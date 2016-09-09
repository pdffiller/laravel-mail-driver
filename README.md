# Pdffiller's Mailer driver for Laravel mail provider.

## Version Compatibility

 Laravel  | Mailer driver | PHP
:---------|:--------------|:----
 5.3.x    | dev-master    |>=5.6.4
 
## Installation
Composer is the recommended way to install this package. Run the following commands in your console:
```

composer config repositories.mail-api-client vcs https://github.com/pdffiller/mail-api-client.git;
composer require pdffiller/mail-api-client:dev-master;
composer config repositories.laravel-mail-driver vcs https://github.com/pdffiller/laravel-mail-driver.git;
composer require pdffiller/laravel-mail-driver:dev-master;
```
Once composer has installed the package add this line of code to the **providers** array located in your **config/app.php** file:
```
Pdffiller\LaravelMailDriver\MailServiceProvider::class,
```
Next step is add this lines of code to the main array located in your **config/services.php** file:
```
'pdffiller' => [
    'host'          => env('PDFFILLER_MAIL_HOST'),
    'schema'        => env('PDFFILLER_MAIL_SCHEMA'),
    'username'      => env('PDFFILLER_MAIL_USERNAME'),
    'password'      => env('PDFFILLER_MAIL_PASSWORD'),
]
```
Set into variable **MAIL_DRIVER** the value **pdffiller** in your .env file:
```
MAIL_DRIVER=pdffiller
``` 
