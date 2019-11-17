# Ably Broadcaster for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://packagist.org/packages/springboardvr/laravel-ably-broadcaster)
[![Build Status](https://img.shields.io/travis/springboardvr/laravel-ably-broadcaster/master.svg?style=flat-square)](https://travis-ci.org/springboardvr/laravel-ably-broadcaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://scrutinizer-ci.com/g/springboardvr/laravel-ably-broadcaster)
[![Total Downloads](https://img.shields.io/packagist/dt/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://packagist.org/packages/springboardvr/laravel-ably-broadcaster)

Adding support for the [Ably](https://ably.io) broadcaster to Laravel! This uses the native [Ably PHP SDK](https://github.com/ably/ably-php) and adds a custom Laravel Broadcast Driver.

## Installation

You can install the package via composer:

```bash
composer require springboardvr/laravel-ably-broadcaster
```



### Configuration
Currently to use Ably with Laravel Echo in the frontend you need to enable the Pusher Protocol Support inside of your Abbly account.

1. Go to Settings for your Application
2. Enable Pusher protocol support under Protocol Adapter Settings

Once you've got that setup you can continue to configuring your Laravel application.

Change your default Broadcast driver name in `config/broadcasting.php` 
```php
'default' => env('BROADCAST_DRIVER', 'ably'),
```

Then you need to add Ably to your `config/broadcasting.php` config file under `connections`.

```php
'ably' => [
    'driver' => 'ably',
    'key' => env('ABLY_KEY'),
],
```

Then you need to update your `.env` file with your Ably configuration details. The Key is available in the API Keys section of Ably. You need a key with full Privileges. 

The `ABLY_KEY` value will look something like `g7CSSj.E08Odw:t2w2LkZ7OcR2Xk7S`
For the `MIX_ABLY_KEY` value you need to take everything before the `:` in your `ABLY_KEY`, like `g7CSSj.E08Odw` 
  
```bash
BROADCAST_DRIVER=ably
ABLY_KEY=
MIX_ABLY_KEY=
```

Once you've got the Laravel side setup you also need to update Laravel Echo to use Ably! It keeps using the Pusher JS library but you use the Websocket Host that Ably provides.


```javascript
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_ABLY_KEY,
    wsHost: 'realtime-pusher.ably.io',
    wsPort: 443,
    disableStats: true,
    encrypted: true,
});
```

That's it! Public, Private, and Presence channels will all work as with Pusher.

### Testing
``` bash
composer test
```

### Limitations
- Currently in the frontend it is using the PusherJS library rather then the Ably library. We will be evaluating adding support for this library to Laravel Echo in the future.
- When you are broadcasting to multiple channels we aren't yet using the [Bulk Publish](https://www.ably.io/documentation/rest-api/beta#batch-publish) endpoints. Once these are moved out of Beta we will update the library to support them.
- Limited testing! Needs to be expanded to cover the Auth functions. 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email matthew@springboardvr.com instead of using the issue tracker.

## Credits

- [Matthew Hall](https://github.com/springboardvr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
