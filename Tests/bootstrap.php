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

$loader = require __DIR__.'/../../../../vendor/autoload.php';

$envFile = __DIR__.'/../../../../.env';
if (file_exists($envFile)) {
    (new Symfony\Component\Dotenv\Dotenv())
        ->usePutenv()
        ->bootEnv($envFile);
}
