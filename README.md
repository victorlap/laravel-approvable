# Laravel Approvable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Easily add an approval process to any laravel model


## Install

Via Composer

``` bash
$ composer require victorlap/laravel-approvable
```

Next, you must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Victorlap\Approvable\ApprovableServiceProvider::class,
];
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Victorlap\Approvable\ApprovableServiceProvider" --tag="migrations"
```

*Note*: The default migration assumes you are using integers for your model IDs. If you are using UUIDs, or some other format, adjust the format of the approvable_id and user_id fields in the published migration before continuing.

After the migration has been published you can create the `approvals` table by running the migrations:


```bash
php artisan migrate
```

## Usage

 1. Add the trait to your eloquent model.
 2. (Optional) Specify the `approveOf` or `dontApproveOf` array to skip certain fields.

## Example
```php
use Illuminate\Database\Eloquent\Model;
use Victorlap\Approvable\Approvable;

class User extends Model
{
    use Aprrovable;

    protected $approveOf = array();

    protected $dontApproveOf = array();
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email victorlap@outlook.com instead of using the issue tracker.

## Credits

- [Victor Lap][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/victorlap/laravel-approvable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/victorlap/laravel-approvable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/victorlap/laravel-approvable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/victorlap/laravel-approvable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/victorlap/laravel-approvable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/victorlap/laravel-approvable
[link-travis]: https://travis-ci.org/victorlap/laravel-approvable
[link-scrutinizer]: https://scrutinizer-ci.com/g/victorlap/laravel-approvable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/victorlap/laravel-approvable
[link-downloads]: https://packagist.org/packages/victorlap/laravel-approvable
[link-author]: https://github.com/victorlap
[link-contributors]: ../../contributors
