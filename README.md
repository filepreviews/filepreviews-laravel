# filepreviews-laravel

Laravel 5 service provider for FilePreviews.io

## Installation

```
$ composer require filepreviews/filepreviews-laravel
```
To use the FilePreviews Service Provider, you must register the provider when bootstrapping your Laravel application.

Find the `providers` key in your `config/app.php` and register the FilePreviews Service Provider.

```php
    'providers' => [
        // ...
        
        FilePreviews\Laravel\FilePreviewsServiceProvider::class,
    ]
```

Find the `aliases` key in your `config/app.php` and add the FilePreviews facade alias.

```php
    'aliases' => [
        // ...
        
        'FilePreviews' => FilePreviews\Laravel\FilePreviewsFacade::class,
    ]
```

To customize the configuration file, publish the package configuration using Artisan.

```
$ php artisan vendor:publish
```

Update your settings in the generated `config/filepreviews.php` configuration file.

```php
<?php

return [
    'api_key' => env('FILEPREVIEWS_API_KEY', ''),
    'api_secret' => env('FILEPREVIEWS_API_SECRET', '')
];
```

## Usage

In order to use the [FilePreviews PHP client library](https://github.com/GetBlimp/filepreviews-php) within your app, you need to resolve it from the [Laravel Service Container](http://laravel.com/docs/5.1/container#resolving).

```php
$fp = app('FilePreviews');
$fp->generate($url, $options);
```

## Handling Webhooks

Point a route to the controller.

```php
Route::post('filepreviews/webhook', '\FilePreviews\Laravel\WebhookController@handleWebhook');
```

Since FilePreviews webhooks need to bypass Laravel's [CSRF verification](http://laravel.com/docs/5.1/routing#csrf-protection), be sure to list the URI as an exception in your `VerifyCsrfToken` middleware:

```php
protected $except = [
    'filepreviews/webhook',
];
```
