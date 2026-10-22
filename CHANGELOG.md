# Changelog: Coupon Import for Magento 2

All releases of this extension, newest first. The released versions are commits on `main`, each tagged with its version number (e.g. [`100.0.11`](https://github.com/PluginCompany/magento2-coupon-import/releases/tag/100.0.11)); Composer installs these tags.

Tag `100.0.1` has no entry in the published changelog.

## 100.0.11 — 2026-10-23

- PHP 8.1–8.3 compatibility: empty lines in an imported coupon file and an empty pasted coupon list are no longer passed as `null` to `trim()` / `explode()` (deprecated since PHP 8.1, which made the import fail).
- `composer.json` now allows PHP 8.0 – 8.3.
- Security fix: the import endpoints now require an admin login and the Cart Price Rules permission. Earlier versions let unauthenticated requests reach them.
- Verified by static analysis (PHPCompatibility, php -l on PHP 7.4, 8.1 and 8.3); not run on a live store.

## 100.0.10 — 2020-08-24

- Added more descriptive error message when user tries to import coupons before saving the cart price rule.

## 100.0.9 — 2020-08-18

- Fix for coupon import tab not showing when auto generation already selected in Magento 2.1.x

## 100.0.8 — 2020-08-11

- Verified compatibility with Magento 2.4.x and PHP 7.4
- Increased coupon import textarea height in admin area

## 100.0.7 — 2020-07-12

- Verified compatibility with Magento ECE / EE 2.3.5
- Updated PHP version compatibility in composer.json

## 100.0.6 — 2019-07-25

- Fixed compatibility with Magento 2.3 new 'Auto' coupon type
- Fixed importing issue in Magento 2.3.x EE / Cloud
- Fixed import coupon section styling for Magento EE / Cloud

## 100.0.5 — 2018-07-09

- License manager upgrade

## 100.0.4 — 2017-10-27

- Magento 2.0 license manager compatibility fix

## 100.0.3 — 2017-09-27

- Magento 2.2 support
- Error handling improvement

## 100.0.2 — 2017-08-31

- Small bug fixes
- Modified composer file

## 100.0.0 — 2016-10-04

- First release of Magento 2 Coupon Import extension by Plugin Company.
