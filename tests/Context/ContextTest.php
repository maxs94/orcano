<?PHP 
declare(strict_types=1);

namespace App\Tests\Context;

use App\Context\Context;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ContextTest extends TestCase
{
    public function testCreateContextFromRequest(): void 
    {
        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->setSession($this->createMock('Symfony\Component\HttpFoundation\Session\SessionInterface'));

        $context = Context::createContextFromRequest($request);

        $this->assertEquals('test', $context->getActiveRoute());
        $this->assertEquals('/', $context->getPathInfo());
        $this->assertEquals(null, $context->getCurrentUser());
    }

}
