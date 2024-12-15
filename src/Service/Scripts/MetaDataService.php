<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\DataObject\Scripts\MetaDataObject;
use App\Entity\CheckScriptParameter;
use App\Exception\MetaDataNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class MetaDataService
{
    public const MAX_LINES_TO_READ = 20;
    public const VALID_DATATYPES = ['string', 'int', 'float', 'bool'];

    private string $commentStartsWith = '#';

    public function __construct(
        private readonly LoggerInterface $logger
    ) { }

    /**
     * @param array<string> $validKeys
     */
    public function extractMetaDataFromFile(string $filename, array $validKeys): MetaDataObject
    {
        $lines = 0;
        $metaData = [];

        if (!file_exists($filename)) {
            throw new FileNotFoundException(null, 0, null, $filename);
        }

        $file = fopen($filename, 'r');

        while (($line = fgets($file)) !== false) {
            if (feof($file)) {
                break;
            }

            $data = $this->extractMetaDataFromString($line, $validKeys);

            if ($data !== []) {
                $metaData = array_merge($metaData, $data);
            }

            if ($lines++ >= self::MAX_LINES_TO_READ) {
                break;
            }
        }

        if ($metaData === []) {
            throw new MetaDataNotFoundException(null, 0, null, $filename);
        }

        $parameters = $this->parseParameterString($metaData['parameters']);

        return (new MetaDataObject())
            ->setFilename($filename)
            ->setName($metaData['name'])
            ->setDescription($metaData['desc'])
            ->setParameters($parameters)
        ;
    }

    /** @return array<CheckScriptParameter> */
    private function parseParameterString(string $parameterString): array
    {
        $parameters = [];
        $parameterString = trim($parameterString);

        $parts = explode(',', $parameterString);

        // parameterString is 'parameter1<string>, parameter2<int>'
        foreach ($parts as $part) {
            $part = trim($part);
            $parts2 = explode('<', $part);
            if (count($parts2) !== 2) {
                continue;
            }
            $key = trim($parts2[0]);
            $type = trim($parts2[1]);
            $type = str_replace('>', '', $type);

            if (!in_array($type, self::VALID_DATATYPES, true)) {
                $this->logger->warning(sprintf('Parameter %s has invalid type %s', $key, $type));
                continue;
            }

            $checkScriptParameter = new CheckScriptParameter();
            $checkScriptParameter->setName($key);
            $checkScriptParameter->setDataType($type);

            $parameters[] = $checkScriptParameter;

        }

        return $parameters;

    }

    /**
     * @param array<string> $validKeys
     *
     * @return array<string>
     */
    public function extractMetaDataFromString(string $content, array $validKeys): array
    {
        $metaData = [];
        $content = trim($content);
        if (str_starts_with($content, $this->commentStartsWith)) {
            $content = substr($content, strlen($this->commentStartsWith));
            $content = trim($content);
            $content = explode(':', $content, 2);
            if (count($content) === 2) {
                $key = trim($content[0]);
                $value = trim($content[1]);
                if (in_array($key, $validKeys, true)) {
                    $metaData[$key] = $value;
                }
            }
        }

        return $metaData;
    }

    public function setCommentStartsWith(string $startsWith): self
    {
        $this->commentStartsWith = $startsWith;

        return $this;
    }

    public function getCommentStartsWith(): string
    {
        return $this->commentStartsWith;
    }
}
