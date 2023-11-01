<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Context;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class ContextLoader
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Security $security
    ) {}

    public function update(): void
    {
        $user = $this->security->getUser();
        $user = $this->getCleanUserObject($user);
        $session = $this->requestStack->getSession();

        $session->set('currentUser', $user);
    }

    private function getCleanUserObject(UserInterface $user): UserInterface
    {
        /** @var User $user */
        $cleanUser = clone $user;
        $cleanUser->setPassword('');

        return $cleanUser;
    }
}
