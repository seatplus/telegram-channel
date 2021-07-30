# :package_description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/seatplus/telegram-channel.svg?style=flat-square)](https://packagist.org/packages/seatplus/telegram-channel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/seatplus/telegram-channel/run-tests?label=tests)](https://github.com/seatplus/telegram-channel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/seatplus/telegram-channel/Check%20&%20fix%20styling?label=code%20style)](https://github.com/seatplus/telegram-channel/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/seatplus/telegram-channel.svg?style=flat-square)](https://packagist.org/packages/seatplus/telegram-channel)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require seatplus/telegram-channel
```

You run the migrations with:

```bash
php artisan migrate
```

## Configuration

1. create a bot by contacting [@BotFather](http://t.me/BotFather) (opens new window) (https://core.telegram.org/bots#6-botfather)
2. set your website domain using `/setdomain` (f.e. http://yourdomain.tld)
3. set `.env` variables
```dotenv
TELEGRAM_BOT_NAME=
TELEGRAM_TOKEN=
TELEGRAM_REDIRECT_URI=http://yourdomain.tld/auth/telegram/callback
```
make sure to replace `yourdomain.tld` with your actual domain.


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Herpaderp Aldent](https://github.com/herpaderpaldent)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
