<?php

namespace App\Repository;

use App\DataObject\ScriptResultDataObject;
use App\Entity\CheckResult;
use App\Service\DataTransformer\StringDataTransformer;
use App\Service\MySqlTypeService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method CheckResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckResult[]    findAll()
 * @method CheckResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckResultRepository extends AbstractServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckResult::class);
    }

    public function updateCheckResultTableStructure(ScriptResultDataObject $scriptResult, string $serviceCheckName): void
    {
        $scriptMessage = $scriptResult->getMessage();

        if (!is_array($scriptMessage)) {
            return;
        }

        $tableName = $this->transformTableName($serviceCheckName);

        $existingColumns = $this->getTableColumns($tableName);

        if (empty($existingColumns)) {

            $q = 'CREATE TABLE ' . $tableName . ' (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `check_result_id` INT NOT NULL,
                `created_at` DATETIME NOT NULL,';

            $q .= $this->createFieldsSql($existingColumns, $scriptMessage);

            $q .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ';

        } else {

            $qFields = $this->createFieldsSql($existingColumns, $scriptMessage);
            if ($qFields !== '') {
                $q = 'ALTER TABLE ' . $tableName . ' ADD ' . $qFields;
            }

        }

        if (!empty($q)) {
            $em = $this->getEntityManager();
            $conn = $em->getConnection();
            $stmt = $conn->prepare($q);
            $stmt->executeStatement();
        }
    }

    /** 
     * @param array<int, array<string, mixed>> $existingColumns
     * @param array<string, string> $scriptMessage
     **/
    private function createFieldsSql(array $existingColumns, array $scriptMessage): string 
    {
        $fields = [];
        foreach ($scriptMessage as $key => $value) {

            // check for existing column
            if (array_key_exists($key, $existingColumns)) {
                continue;
            }

            $mysqlType = MySqlTypeService::getTypeOfString($value);
            $fields[$key] = sprintf('`%s` %s', $key, $mysqlType);
        }

        $fields = array_unique($fields);

        return implode(',', $fields);

    }

    /** @return array<int, array<string, mixed>> */
    private function getTableColumns(string $tableName): array 
    {
        $q = 'DESC ' . $tableName;
        $stmt = $this->getEntityManager()->getConnection()->prepare($q);

        $results = $stmt->executeQuery();

        if ($results->rowCount() === 0) {
            return [];
        }

        return $results->fetchAllAssociativeIndexed();
    }

    private function transformTableName(string $serviceCheckName): string 
    {
        $transformedServiceCheckName = StringDataTransformer::transformStringToLatin($serviceCheckName);
        if (empty($transformedServiceCheckName)) {
            throw new Exception('Transformed service check name is empty. Cannot create a result table without a valid serviceCheckName.');
        }

        $tableName = strtolower($transformedServiceCheckName) . '_results';

        if (strlen($tableName) > 64) {
            throw new Exception('Result table name is too long. Cannot create a result table without a valid serviceCheckName. Make sure it is not longer than 56 characters.');
        }

        return $tableName;
    }


}
