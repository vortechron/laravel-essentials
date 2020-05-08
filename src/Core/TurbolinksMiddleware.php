<?php

/*
 * This file is part of the Helthe Turbolinks package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vortechron\Essentials\Core;

use Closure;

/**
 * Stack middleware for Turbolinks.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class TurbolinksMiddleware
{
    const MASTER_REQUEST = 1;
    const SUB_REQUEST = 2;
    /**
     * @var HttpKernelInterface
     */
    private $app;

    /**
     * @var Turbolinks
     */
    private $turbolinks;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $app
     * @param Turbolinks          $turbolinks
     */
    public function __construct(Turbolinks $turbolinks)
    {
        $this->turbolinks = $turbolinks;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($request, Closure $next, $type = self::MASTER_REQUEST, $catch = true)
    {
        $response = $next($request, $type, $catch);

        if (self::MASTER_REQUEST === $type) {
            $this->turbolinks->decorateResponse($request, $response);
        }

        return $response;
    }
}