<?PHP 
declare(strict_types=1);

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

    #[Route('/condition-builder/parameters', name: 'condition_builder_parameters')]
    public function conditionBuilderParameters(Request $request): Response
    {
        $availableConditions = $this->conditionService->getAllAvailableConditions();
        
        $className = $request->query->get('condition');

        $condition = $availableConditions[$className] ?? null;
        if ($condition === null) {
            throw new \Exception('Could not find condition ' . $className);
        }

        return $this->renderPage('condition-builder/parameters.html.twig', [
            'condition' => $condition,
        ]);
    }
    
}
