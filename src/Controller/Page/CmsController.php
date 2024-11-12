<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\Page;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cms')]
class CmsController extends AbstractPageController
{
    #[Route('/{name}', name: 'pages')]
    public function index(string $name): Response
    {
        return $this->renderTwigTemplate($name);
    }

    private function renderTwigTemplate(string $pageName): Response
    {
        $filename = sprintf('pages/%s.html.twig', $pageName);
        $path = sprintf('%s/templates/%s', $this->getParameter('kernel.project_dir'), $filename);
        if (!file_exists($path)) {
            return $this->redirectToRoute('page_not_found');
        }

        return $this->renderPage($filename);
    }
}
