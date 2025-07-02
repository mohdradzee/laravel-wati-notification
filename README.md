
# 📲 WATI Notification Channel for Laravel

Send WhatsApp notifications via [WATI API](https://docs.wati.io) using Laravel's native notification system.

---

## ✨ Features

- Send WhatsApp messages via WATI using Laravel Notifications
- Custom notification channel (`wati`)
- Built-in support for multiple WATI API actions
- Clean, elegant API (`$user->notify(...)`)
- Easily extendable for new WATI endpoints
- Emits Laravel's `NotificationFailed` event on HTTP failures

---

## 📦 Installation

```bash
composer require mohdradzee/wati-notification
```

If Laravel doesn't auto-discover the service provider, add this manually:

```php
// config/app.php

'providers' => [
    mohdradzee\WatiNotification\WatiNotificationServiceProvider::class,
],
```

---

## ⚙️ Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=wati-config
```

This creates `config/wati.php`:

```php
return [
    'token' => env('WATI_TOKEN'),
    'base_url' => env('WATI_BASE_URL', 'https://live-server.wati.io/441627/api/v1'),
];
```

Add the following to your `.env`:

```env
WATI_TOKEN=your_api_token_here
WATI_BASE_URL=https://live-server.wati.io/441627/api/v1
```

---

## 🛠️ Create a Demo Notification

### Step 1: Generate Notification

```bash
php artisan make:notification ConfirmWhatsappNumber
```

### Step 2: Edit `app/Notifications/ConfirmWhatsappNumber.php`

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use mohdradzee\WatiNotification\WatiMessage;

class ConfirmWhatsappNumber extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['wati'];
    }

    public function toWati($notifiable)
    {
        return WatiMessage::create()
            ->to($notifiable->phone)
            ->withAction('add_contact')
            ->withMeta([
                'fullName' => $notifiable->name,
                'customParams' => [
                    'source' => 'signup_page',
                ],
            ]);
    }
}
```

---

## 👤 Usage in a Notifiable Model

Make sure your model uses the `Notifiable` trait:

```php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Ensure `phone` attribute exists
}
```

Then notify the user:

```php
$user = App\Models\User::first();
$user->notify(new App\Notifications\ConfirmWhatsappNumber());
```

---

## ✅ WatiMessage API

```php
WatiMessage::create()
    ->to('601122334455')
    ->withAction('send_template')
    ->withTemplate('template_name')
    ->withBroadcastName('broadcast1')
    ->withParameters([
        ['name' => 'name', 'value' => 'alibaba']
    ]);
```

---

## 🔌 Strategy Support (Dynamic API Calls)

Each WATI API action is handled by its own **strategy class**:

| Action            | Description                         | Strategy Class                     |
|-------------------|-------------------------------------|------------------------------------|
| `send_template`   | Send template message               | `SendTemplateStrategy`             |
| `add_contact`     | Add a WhatsApp contact              | `AddContactStrategy`               |

You can add your own by implementing:

```php
use mohdradzee\WatiNotification\Contracts\WatiApiStrategy;

class MyCustomStrategy implements WatiApiStrategy
{
    public function execute(array $data): array
    {
        // call your custom WATI endpoint...
    }
}
```

Then register:

```php
WatiApiExecutor::register('my_action', MyCustomStrategy::class);
```

---

## 🧪 Testing in Tinker

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$user->notify(new App\Notifications\ConfirmWhatsappNumber());
```

---

## 📂 License

MIT License © [mohdradzee](https://github.com/mohdradzee)