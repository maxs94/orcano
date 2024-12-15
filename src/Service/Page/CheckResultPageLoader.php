<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Page;

use App\Context\Context;
use App\DataObject\Page\PageDataObject;
use App\DataObject\Page\PageDataObjectInterface;
use App\Entity\CheckResult;
use App\Repository\CheckResultRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckResultPageLoader
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CheckResultRepository $checkResultRepository,
    ) {}

    public function load(Request $request, Context $context, int $id = null): PageDataObjectInterface
    {
        $title = $this->translator->trans('title.check-result.detail');

        $checkResult = is_null($id) ? new CheckResult() : $this->getCheckResult($id);

        if (!$checkResult instanceof CheckResult) {
            throw new \Exception('Check result not found');
        }

        $checkResultDetails = $this->checkResultRepository->fetchDetailsByCheckResult($checkResult);

        return (new PageDataObject())
            ->setTitle($title)
            ->addParameter('checkResult', $checkResult)
            ->addParameter('checkResultDetails', $checkResultDetails)
        ;
    }

    private function getCheckResult(int $id): ?CheckResult
    {
        return $this->checkResultRepository->find($id);
    }
}
