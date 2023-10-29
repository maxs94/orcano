<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Context;

use App\DataObject\DataObjectInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class Context implements DataObjectInterface
{
    private string $activeRoute;

    private string $pathInfo;

    private ?User $currentUser = null;

    public static function createContextFromRequest(Request $request): Context
    {
        $context = new self();
        $context->activeRoute = $request->attributes->get('_route');
        $context->pathInfo = $request->getPathInfo();

        $session = $request->getSession();
        $context->currentUser = $session->get('currentUser');

        return $context;
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
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
