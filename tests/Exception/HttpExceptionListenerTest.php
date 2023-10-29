<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Exception;

use App\Exception\HttpExceptionListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @internal
 *
 * @covers \App\Exception\HttpExceptionListener
 */
class HttpExceptionListenerTest extends TestCase
{
    public function testListener(): void
    {
        /** @var MockObject|Request $request */
        $request = $this->createMock(Request::class);
        $request->method('getRequestUri')->willReturn('http://localhost/api/test');

        $event = new ExceptionEvent(
            $this->createMock(\Symfony\Component\HttpKernel\HttpKernelInterface::class),
            $request,
            1,
            new \Exception()
        );

        $listener = new HttpExceptionListener();

        $listener->onKernelException($event);

        $response = $event->getResponse();

        $this->assertSame(500, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertFalse($data['success']);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testNotApiRequest(): void
    {
        /** @var MockObject|Request $request */
        $request = $this->createMock(Request::class);
        $request->method('getRequestUri')->willReturn('http://localhost/test');

        $event = new ExceptionEvent(
            $this->createMock(\Symfony\Component\HttpKernel\HttpKernelInterface::class),
            $request,
            1,
            new \Exception()
        );

        $listener = new HttpExceptionListener();

        $listener->onKernelException($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }
}
