# Laravel Approvable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![StyleCI](https://styleci.io/repos/80375034/shield?branch=master)](https://styleci.io/repos/80375034)
[![Total Downloads][ico-downloads]][link-downloads]

Easily add an approval process to any laravel model.

## Description

Laravel Approvable is a package which helps when you have certain models in your application that should be editable by users, but the fields that they edit need to be approved first.

## Installation

Via Composer

``` bash
$ composer require victorlap/laravel-approvable
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Victorlap\Approvable\ApprovableServiceProvider" --tag="migrations"
php artisan migrate
```

## Setup
We have a `Post` model. Each visitor on your site can edit any post, but before you want to publish the change to your website, you want to approve it first. Here comes this package into play. By adding the `\Victorlap\Approvable\Approvable` trait to your `Post` model, when a visitor makes a change, we store a change request in the database. These changes can then later be applied, or denied by administrators. The  `currentUserCanApprove` method can be used to determine who is authorized to make a change.

```php
use Illuminate\Database\Eloquent\Model;
use Victorlap\Approvable\Approvable;

// Minimal
class Post extends Model
{
    use Approvable;   
}

// Extended
class Post extends Model
{
    use Approvable;

    protected $approveOf = array();

    protected $dontApproveOf = array();
    
    protected function currentUserCanApprove()
    {
        return Auth::check();
    }
    
    protected function getSystemUserId()
    {
        return Auth::id();
    }
}
```

## Usage
Making a change to a model by a user who can approve does not change.
```php
$post->title = "Very Good Post";
$post->save(); // This still works!
```

Making a change by an unauthorized user works the same.
```php
$post->title = "Very Good Post";
$post->save(); // Post remains with the old title in the database, however a change request is now also present.
```

You can retrieve a list of attributes that have pending changes by using
```php
$post->getPendingApprovalAttributes();
```

Or check if a certain attribute has pending changes
```php
$post->isPendingApproval('title');
```

Scopes have been defined to quickly see approvals in different states. For example if you wnat to show administrators a list with changes that can be accepted you can use the `open` scope. Other scopes are `accepted` and `rejected` and `ofClass`.
```php
Approval::open()->get();
Approval::accepted()->get();
Approval::rejected()->get();
Approval::ofClass(Post::class)->get();
```

You can combine the scopes of course
```php
Approval::open()->ofClass(Post::class)->get();
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
