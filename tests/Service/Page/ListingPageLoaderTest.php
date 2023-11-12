<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Tests\Service\Page;

use App\Context\Context;
use App\DataObject\Page\ListingPageDataObject;
use App\Entity\User;
use App\Repository\AbstractServiceEntityRepository;
use App\Service\Page\ListingPageLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 *
 * @covers \App\Service\Page\ListingPageLoader
 */
class ListingPageLoaderTest extends TestCase
{
    private ListingPageLoader $service;

    public function setUp(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $em = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($this->createMock(AbstractServiceEntityRepository::class));

        $this->service = new ListingPageLoader($em, $translator);
    }

    public function testLoad(): void
    {
        $request = new Request();
        $request->attributes->set('_route', 'test');
        $request->setSession($this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class));

        $entityName = 'user';
        $requestStack = $this->createMock(RequestStack::class);

        $user = new User();

        $context = new Context($requestStack);
        $context->setCurrentUser($user);

        $result = $this->service->load($request, $entityName, $context);

        $this->assertInstanceof(ListingPageDataObject::class, $result);
    }
}
