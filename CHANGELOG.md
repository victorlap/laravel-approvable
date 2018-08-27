# Changelog

All Notable changes to `laravel-approvable` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Version 1.1.0 - 2018-08-27
Added:
- `withApproval()` method.
- `withoutApproval()` is now chainable.

Deprecated:
- `withoutApproval($withoutApproval = true)` the `$withoutApproval` flag is now deprecated, use the `withApproval()` method if you want to re-enable the approval process.

## Version 1.0.0 - 2018-05-18
Initial release.