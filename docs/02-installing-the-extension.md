# Installing the Magento 2 Extension

## Download and Extract the Extension

Download the latest release of the extension from the [releases page](https://github.com/PluginCompany/magento2-coupon-import/releases) of its GitHub repository (the source code ZIP of the most recent version tag) and extract it locally to any folder. The extracted folder contains the module files, with `registration.php` in its root.

## Upload Files to Server

Connect to your server using SSH, SFTP or FTP.

Create the directory `app/code/PluginCompany/CouponImport` in your Magento 2 root directory and copy the extracted files into it, so that `app/code/PluginCompany/CouponImport/registration.php` exists. The `docs/` folder and the Markdown files (`README.md`, `CHANGELOG.md`) are not needed and can be left out.

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

[Documentation index](./README.md) · [README](../README.md) · [Online version](https://plugin.company/knowledge-base/docs/magento2-coupon-import/installation-guide/installing-the-extension/)
