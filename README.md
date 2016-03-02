# Lencryption

libsodium based alternative to Laravel`s Crypt

## Installation

Add `bitbeans/lencryption` to `composer.json`.

```
"bitbeans/lencryption": "dev-master"
```

Run `composer update` to pull down the latest version of Yubikey.

Now open up `PROJECTFOLDER/config/app.php` and add the service provider to your `providers` array.
```php
'providers' => array(
	Bitbeans\Lencryption\LencryptionServiceProvider::class,
)
```

And also the alias.
```php
'aliases' => array(
	'LCrypt' => Bitbeans\Lencryption\LencryptionFacade::class,
)
```


## Configuration

Run `php artisan vendor:publish` and modify the config file (PROJECTFOLDER/config/lencryption.php) with your own information.


## Example

```php
use LCrypt;

LCrypt::encrypt("test");

LCrypt::decrypt("eyJub25jZSI6ImZmOHZVNXN3VlExWkJQMTJTalI2ZmpKXC83WGExd3F1dCIsImNpcGhlcnRleHQiOiIwcDZEWWtYeXE0YmJlTVBtcndqN3lzbUdMRjk5SUpsWW5QMVYifQ==");

```
