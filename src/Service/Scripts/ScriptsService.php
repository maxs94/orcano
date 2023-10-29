<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\DataObject\Collection\DataObjectCollection;
use App\DataObject\Collection\DataObjectCollectionInterface;
use App\Entity\CheckScript;
use App\Repository\CheckScriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ScriptsService
{
    public const VALID_SCRIPT_EXTENSIONS = [
        'sh',
        'py',
    ];

    public const VALID_METADATA_KEYS = [
        'name',
        'desc',
    ];

    public function __construct(
        private readonly string $checkScriptsPath,
        private readonly ParameterBagInterface $parameterBag,
        private readonly MetaDataService $metaDataService,
        private readonly HashService $hashService,
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger
    ) {}

    public function refreshScripts(): bool
    {
        try {
            $scripts = $this->getAllScriptsFromFilesystem();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        if ($scripts->getCount() === 0) {
            $this->logger->warning('No scripts found in ' . $this->checkScriptsPath);

            return true;
        }

        try {
            $this->upsertCheckScripts($scripts);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }

    public function getAllScriptsFromFilesystem(): DataObjectCollectionInterface
    {
        $scripts = [];

        $dir = $this->parameterBag->get('kernel.project_dir') . '/' . $this->checkScriptsPath;

        foreach (glob($dir . '/*') as $script) {
            if ($this->isValidScript($script) === false) {
                $this->logger->warning('Invalid script found: ' . $script);
                $this->logger->warning('Valid script extensions are: ' . implode(', ', self::VALID_SCRIPT_EXTENSIONS));
                continue;
            }

            try {
                $metaData = $this->metaDataService->extractMetaDataFromFile($script, self::VALID_METADATA_KEYS);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                continue;
            }

            $filehash = $this->hashService->createHashFromFile($script);
            $relativePath = str_replace($this->parameterBag->get('kernel.project_dir') . '/', '', $script);

            $scriptObj = (new CheckScript())
                ->setFilename($relativePath)
                ->setName($metaData->getName())
                ->setDescription($metaData->getDescription())
                ->setFilehash($filehash)
            ;

            $scripts[] = $scriptObj;
        }

        return new DataObjectCollection($scripts);
    }

    public function getAllScripts(): DataObjectCollectionInterface
    {
        /** @var CheckScriptRepository $checkScriptRepository */
        $checkScriptRepository = $this->em->getRepository(CheckScript::class);

        $dbCheckScripts = $checkScriptRepository->findAllAsCollection();
        $filesystemCheckScripts = $this->getAllScriptsFromFilesystem();

        $scripts = [];

        /** @var CheckScript $filesystemCheckScript */
        foreach ($filesystemCheckScripts as $filesystemCheckScript) {
            $script = $dbCheckScripts->findOneBy('filename', $filesystemCheckScript->getFilename());

            if (!$script instanceof CheckScript) {
                $script = $filesystemCheckScript;
                $script->setIsChangedInFilesystem(true);
            } else {
                $script->setIsChangedInFilesystem($script->getFilehash() !== $filesystemCheckScript->getFilehash());
            }

            $scripts[] = $script;
        }

        return new DataObjectCollection($scripts);
    }

    private function upsertCheckScripts(DataObjectCollectionInterface $scripts): void
    {
        $checkScriptRepository = $this->em->getRepository(CheckScript::class);

        /** @var CheckScript $script */
        foreach ($scripts as $script) {
            $relativePath = str_replace($this->parameterBag->get('kernel.project_dir') . '/', '', $script->getFilename());

            $checkScript = $checkScriptRepository->findOneBy(['filename' => $relativePath]);

            if ($checkScript instanceof CheckScript) {
                if ($script->getFilehash() === $checkScript->getFilehash()) {
                    $this->logger->debug('Script contents have not changed, skipping: ' . $relativePath);
                    continue;
                }
                $this->logger->info('Updating script ' . $relativePath);
            } else {
                $checkScript = new CheckScript();
                $checkScript->setFilename($relativePath);
                $this->logger->info('Registering new script ' . $relativePath);
            }

            $checkScript->setName($script->getName());
            $checkScript->setDescription($script->getDescription());
            $checkScript->setFilehash($script->getFilehash());

            $this->em->persist($checkScript);
        }

        $this->em->flush();
    }

    private function isValidScript(string $scriptFilename): bool
    {
        $pathInfo = pathinfo($scriptFilename);

        return in_array($pathInfo['extension'], self::VALID_SCRIPT_EXTENSIONS);
    }
}
