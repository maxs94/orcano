<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Context;

use App\Context\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @internal
 *
 * @covers \App\Context\Context
 */
class ContextTest extends TestCase
{
    public function testCreateContextFromRequest(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->setSession($this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class));

        /** @var RequestStack&MockObject $requestStack */
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);

        $context = new Context($requestStack);

        $this->assertEquals('test', $context->getActiveRoute());
        $this->assertEquals('/', $context->getPathInfo());
        $this->assertEquals(null, $context->getCurrentUser());
    }
}
