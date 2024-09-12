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

namespace Plugin\StripeWebhook\Webhook;

use Plugin\StripeWebhook\RemoteEvent\Event\StripeEvent;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

class StripeRequestParser extends AbstractRequestParser
{
    public function __construct(
        private readonly string $signatureHeaderName = 'HTTP_STRIPE_SIGNATURE'
    ) {
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new IsJsonRequestMatcher(),
            new MethodRequestMatcher(Request::METHOD_POST),
        ]);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): ?RemoteEvent
    {
        try {
            $signature = $request->server->get($this->signatureHeaderName);
            $event = Webhook::constructEvent($request->getContent(), $signature, $secret);
        } catch (UnexpectedValueException|SignatureVerificationException $exception) {
            throw new RejectWebhookException(406, $exception->getMessage());
        }

        return new StripeEvent(
            name: $event->type,
            id: $event->id,
            payload: $event->data->object->toArray(),
            resource: $event->data->object
        );
    }
}
