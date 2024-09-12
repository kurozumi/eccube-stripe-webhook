<?php

declare(strict_types=1);

/*
 * This file is part of Stripe Webhook
 *
 * Copyright(c) Akira Kurozumi All Rights Reserved.
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\StripeWebhook\RemoteEvent\Event;

use Stripe\StripeObject;
use Symfony\Component\RemoteEvent\RemoteEvent;

final class StripeEvent extends RemoteEvent
{
    public function __construct(
        private readonly string $name,
        private readonly string $id,
        private readonly array $payload,
        private readonly StripeObject $resource
    ) {
        parent::__construct($this->name, $this->id, $this->payload);
    }

    public function getResource(): StripeObject
    {
        return $this->resource;
    }
}
