<?php
declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service\Scripts;

use App\DataObject\Scripts\MetaDataObject;

class MetaDataService
{
    final public const MAX_LINES_TO_READ = 20;
    private string $commentStartsWith = '#';

    /**
     * @param array<string> $validKeys
     */
    public function extractMetaDataFromFile(string $filename, array $validKeys): MetaDataObject
    {
        $lines = 0;
        $metaData = [];

        if (!file_exists($filename)) {
            throw new \Exception('File not found: ' . $filename);
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
            throw new \Exception('No metadata found in file: ' . $filename);
        }

        return (new MetaDataObject())
            ->setFilename($filename)
            ->setName($metaData['name'])
            ->setDescription($metaData['desc'])
        ;
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
