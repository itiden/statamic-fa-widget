# Statamic FA Widget

This is a Statamic widget that shows statistics from Fathom Analytics on your Statamic dashboard. Developed by [Itiden](https://www.itiden.se/en).

## How to install

Require the addon using Composer

```bash
composer require itiden/statamic-fa-widget
```

## How to use

You need to add the following variables to your `.env` file

```Dotenv
FA_API_TOKEN="your-fathom-api-token"
FA_SITE_ID="YOUR-SITE_ID"
FA_HOSTNAME="your-hostname-optional"
```

You can also manage your settings in the configuration file by publishing it:

```sh
php artisan vendor:publish --tag="fa-widget-config"
```

If you don't set a hostname, your results will be for all hostnames your site has.

You also need to add the widget to the widget array in config/statamic/cp.php file, as you would with [any other widget](https://statamic.dev/widgets#configuration).

```php
[
    'type' => 'fa',
    'width' => 100,
],
```

We recommend you use the widget at 100 width.

## API Notice

The Fathom Analytics API is in a early access phase and might change causing this addon to fail.  
We will do our best to make sure it is updated when the API changes.

The fathom analytics API don't yet support filtering for multiple hostnames, so for now only the first will be used.

## Disclaimer

Itiden is not affiliated with Fathom Analytics, nor is it part of the company that owns Fathom Analytics, Conva Ventures Inc.
We have created this widget for our own use in projects and comissions, and simply want to share it with whoever wants to use it
in their project or site.
