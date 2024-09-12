<?php

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

return new Plugin\StripeWebhook\RemoteEvent\Event\StripeEvent(
    name: 'event_name',
    id: 'event_id',
    payload: ['object' => 'test'],
    resource: new Stripe\StripeObject()
);
