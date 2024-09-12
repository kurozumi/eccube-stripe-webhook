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

namespace Plugin\StripeWebhook\Tests\Webhook;

use Plugin\StripeWebhook\Webhook\StripeRequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\RequestParserInterface;
use Symfony\Component\Webhook\Test\AbstractRequestParserTestCase;

class StripeRequestParserTest extends AbstractRequestParserTestCase
{
    /**
     * @dataProvider getPayloads
     */
    public function testParse(string $payload, RemoteEvent $expected)
    {
        $request = $this->createRequest($payload);
        $parser = $this->createRequestParser();
        $wh = $parser->parse($request, $this->getSecret());

        self::assertEquals($expected->getName(), $wh->getName());
        self::assertEquals($expected->getId(), $wh->getId());
        self::assertEquals($expected->getPayload(), $wh->getPayload());
    }

    protected function createRequestParser(): RequestParserInterface
    {
        return new StripeRequestParser();
    }

    protected function getSecret(): string
    {
        return getenv('STRIPE_SIGNING_SECRET');
    }

    protected function createRequest(string $payload): Request
    {
        $timestamp = time();
        $signedPayload = "{$timestamp}.{$payload}";
        $expectedSignature = \hash_hmac('sha256', $signedPayload, $this->getSecret());

        return Request::create('/', 'POST', [], [], [], [
            'Content-Type' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$expectedSignature},v0=signature",
        ], $payload);
    }
}
