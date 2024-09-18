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

namespace Plugin\StripeWebhook\RemoteEvent\Consumer;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer(name: 'stripe')]
class StripeConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function consume(RemoteEvent $event): void
    {
        $this->eventDispatcher->dispatch($event, $event->getName());
    }
}
