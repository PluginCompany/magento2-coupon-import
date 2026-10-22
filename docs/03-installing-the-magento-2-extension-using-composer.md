# Installing the Magento 2 Extension Using Composer

## Add the Plugin Company Composer Repository

Run the following command in your Magento 2 root folder to add the Plugin Company Composer repository to your project's composer.json:

```bash
composer config repositories.plugin.company composer https://plugin.company/
```

The repository is public and points to the extensions' GitHub repositories, so no username or password is required.

## Install the Extension using Composer

Execute the following command to install the extension:

```bash
composer require plugincompany/couponimport
```

Alternatively, you can download the release ZIP from GitHub and install the extension manually (see the manual installation guide).

## Enable the Extension

Enable the extension using the following command:

```bash
php bin/magento module:enable PluginCompany_CouponImport
```

## Update the Database

Run the following upgrade command:

```bash
php bin/magento setup:upgrade
```

## Clean the Magento Cache

Clean the cache using the following command:

```bash
php bin/magento cache:clean
```

## Remove auto-generated classes

Remove all auto-generated classes using the following command:

```bash
rm -Rf ./generated/*
```

Or in Magento 2.0 and 2.1:

```bash
rm -Rf ./var/generation/*
```

## Run the Compiler

Run the dependency injection compilation command:

```bash
php bin/magento setup:di:compile
```

## Deploy Static View Files

Run the static content generation command:

```bash
php bin/magento setup:static-content:deploy
```

---

[Documentation index](./README.md) · [README](../README.md) · [Online version](https://plugin.company/knowledge-base/docs/magento2-coupon-import/installation-guide/installing-the-magento-2-extension-using-composer/)
