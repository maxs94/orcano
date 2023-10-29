<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Page;

use App\Context\Context;
use App\DataObject\Page\ListingPageDataObject;
use App\Service\Page\ListingPageLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
class ListingPageLoaderTest extends TestCase
{
    private ListingPageLoader $service;

    public function setUp(): void
    {
        $this->service = new ListingPageLoader();
    }

    public function testLoad(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->setSession($this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class));

        $entityName = 'test';
        $context = Context::createContextFromRequest($request);

        $result = $this->service->load($request, $entityName, $context);

        $this->assertInstanceof(ListingPageDataObject::class, $result);
    }
}
