# statamic-fa-widget

This is a Statamic widget that shows statistics from usefathom.com on your Statamic dashboard. Developed by itiden.

## How to install

Require the addon using Composer

```bash
composer require itiden/statamic-fa-widget
```

## How to use

You need to add the following variables to your `.env` file

```php
FA_API_TOKEN="your-fathom-api-token"
FA_SITE_ID="YOUR-SITE_ID"
FA_HOSTNAME="your-hostname"
```

You also need to add the widget to the widget array in config/statamic/cp.php file, as you would with [any other widget](https://statamic.dev/widgets#configuration).

```php
[
    'type' => 'fa_widget',
    'width' => 100,
],
```

We recommend you use the widget at 100 width.

## Disclaimer

Itiden is not affiliated with Fathom Analytics, nor is it part of the company that owns Fathom Analytics, Conva Ventures Inc.
We have created this widget for our own use in projects and comissions, and simply want to share it with whoever wants to use it
in their project or site.
