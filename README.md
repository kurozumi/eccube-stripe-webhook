# Stripe Webhook

StripeのWebhookに登録するエンドポイントを構築するためのプラグインです。

## 各種ライブラリをインストール

以下のライブラリをcomposerでインストールしてください。

```bash
composer require symfony/webhook symfony/remote-event stripe/stripe-php
```

## Webhookの署名シークレットを設定

.envにWebhookの署名シークレットを設定してください。

```text
STRIPE_SIGNING_SECRET=whsec_t6KFvU8...
```

StripeWebhookプラグインをEC-CUBEにインストール・有効化してください。

```bash
git clone git@github.com:kurozumi/eccube-stripe-webhook.git app/Plugin
bin/console e:p:i --code StripeWebhook
bin/console e:p:e --code StripeWebhook
```

## エンドポイント

下記のエンドポイントをStripeのWebhookに登録してください。

```text
https://my-domain/webhook/stripe
```

## 定期支払いが成功したり失敗した場合に何かする処理の実装方法

```php
<?php

declare(strict_types=1);

namespace Customize\EventListener;

use Plugin\StripeWebhook\RemoteEvent\Event\StripeEvent;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Invoice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

class StripeEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Event::CHECKOUT_SESSION_COMPLETED => ['onCheckoutSessionCompleted'],
            Event::INVOICE_PAID => ['onInvoicePaid'],
            Event::INVOICE_PAYMENT_FAILED => ['onInvoicePaymentFailed']
        ];
    }

    /**
     * 顧客が「支払う」または「登録」ボタンをクリックしたときに何かする
     *
     * @param StripeEvent $event
     */
    public function onCheckoutSessionCompleted(StripeEvent $event)
    {
        /** @var Session $session */
        $session = $event->getResource();
    }

    /**
     * 支払いが成功した場合に何かする
     *
     * @param StripeEvent $event
     */
    public function onInvoicePaid(StripeEvent $event)
    {
        /** @var Invoice $invoice */
        $invoice = $event->getResource();
    }

    /**
     * 顧客の支払い方法に問題があった場合に何かする
     *
     * @param StripeEvent $event
     */
    public function onInvoicePaymentFailed(StripeEvent $event)
    {
        /** @var Invoice $invoice */
        $invoice = $event->getResource();
    }
}

```

## ご注意

カスタマイズ、または他社プラグインとの競合による動作不良つきましてはサポート対象外です。

本プラグインを導入したことによる不具合や被った不利益につきましては一切責任を負いません。 ご理解の程よろしくお願いいたします。
