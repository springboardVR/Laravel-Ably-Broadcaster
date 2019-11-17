# Ably Broadcaster for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://packagist.org/packages/springboardvr/laravel-ably-broadcaster)
[![Build Status](https://img.shields.io/travis/springboardvr/laravel-ably-broadcaster/master.svg?style=flat-square)](https://travis-ci.org/springboardvr/laravel-ably-broadcaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://scrutinizer-ci.com/g/springboardvr/laravel-ably-broadcaster)
[![Total Downloads](https://img.shields.io/packagist/dt/springboardvr/laravel-ably-broadcaster.svg?style=flat-square)](https://packagist.org/packages/springboardvr/laravel-ably-broadcaster)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require springboardvr/laravel-ably-broadcaster
```

Then you need to add Ably to your `broadcasting.php` config file under `connections`.

```bash
        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ]
```

Once you've setup an account with [Ably](https://www.ably.io/)

Go to Settings for your Application
Enable Pusher protocol support under Protocol Adapter Settings


```javascript
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'appid.keyid',
    wsHost: 'realtime-pusher.ably.io',
    wsPort: 443,
    disableStats: true,
});
```

## Usage

``` php
// Usage description here
```

### Testing

``` bash
composer test
```

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
