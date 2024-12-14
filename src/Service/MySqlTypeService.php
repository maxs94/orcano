<?PHP 
declare(strict_types=1);
/**
 * © 2023-2024 by the orcano team (https://github.com/maxs94/orcano)
 */

namespace App\Service;

use Doctrine\DBAL\ParameterType;

class MySqlTypeService 
{
    public static function getType(mixed $value): string 
    {
        if (is_null($value)) {
            return 'VARCHAR(255)';
        }
        
        if (filter_var($value, FILTER_VALIDATE_FLOAT) || is_float($value)) {
            return 'FLOAT';
        }

        if (filter_var($value, FILTER_VALIDATE_INT) || is_int($value)) {
            return 'INT';
        }
        
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) || is_bool($value)) {
            return 'BOOLEAN';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return 'DATETIME';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return 'DATE';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return 'TIME';
        }

        if (strlen($value) <= 255) {
            return 'VARCHAR(255)';
        }

        if (strlen($value) <= 65535) {
            return 'TEXT';
        }

        if (strlen($value) <= 16777215) {
            return 'LONGTEXT';
        }

        return 'VARCHAR(255)';
    }

    public static function getParameterType(mixed $value): int 
    {
        $type = self::getType($value);

        if ($type === 'INT' || $type == 'FLOAT') {
            return ParameterType::INTEGER;
        }

        if ($type === 'BOOLEAN') {
            return ParameterType::BOOLEAN;
        }

        return ParameterType::STRING;
    }
    
}
