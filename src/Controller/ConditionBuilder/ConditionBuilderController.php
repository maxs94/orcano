<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Controller\ConditionBuilder;

use App\Context\Context;
use App\Controller\Page\AbstractPageController;
use App\Service\Condition\ConditionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionBuilderController extends AbstractPageController
{
    public function __construct(
        Context $context,
        private readonly ConditionService $conditionService
    ) {
        parent::__construct($context);
    }

    #[Route('/condition-builder/parameters', name: 'condition_builder_parameters', methods: ['GET'])]
    public function conditionBuilderParameters(Request $request): Response
    {
        $availableConditions = $this->conditionService->getAllAvailableConditions();

        $all = $request->query->all();
        if (!isset($all['condition'])) {
            throw new \Exception('No condition given');
        }

        $conditions = $all['condition'];

        if (!is_array($conditions)) {
            throw new \Exception('Condition is not an array');
        }

        $serviceCheckId = key($conditions);
        $conditionIndex = key($conditions[$serviceCheckId]);

        return $this->renderPage('condition-builder/parameters.html.twig', [
            'serviceCheckId' => $serviceCheckId,
            'conditionIndex' => $conditionIndex,
            'condition' => $availableConditions[$conditions[$serviceCheckId][$conditionIndex]['name']],
        ]);
    }
}
