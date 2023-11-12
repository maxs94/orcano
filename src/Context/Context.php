<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Context;

use App\DataObject\DataObjectInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class Context implements DataObjectInterface
{
    private string $activeRoute;

    private string $pathInfo;

    private ?User $currentUser = null;

    public function __construct(RequestStack $requestStack = null)
    {
        if ($requestStack instanceof \Symfony\Component\HttpFoundation\RequestStack) {
            $request = $requestStack->getCurrentRequest();
            if ($request instanceof \Symfony\Component\HttpFoundation\Request) {
                $this->activeRoute = $request->attributes->get('_route');
                $this->pathInfo = $request->getPathInfo();

                $session = $request->getSession();
                $this->currentUser = $session->get('currentUser');
            }
        }
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }

    public function setCurrentUser(User $currentUser): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    public function getActiveRoute(): string
    {
        return $this->activeRoute;
    }

    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }
}
