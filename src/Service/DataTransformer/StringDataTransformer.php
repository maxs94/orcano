<?PHP 
declare(strict_types=1);

namespace App\Service\DataTransformer;

use Symfony\Polyfill\Intl\Normalizer\Normalizer;

class StringDataTransformer 
{
    public static function transformStringToLatin(string $input): string 
    {
        // Normalize the input to decompose combined characters (NFD)
        $input = Normalizer::normalize($input, \Normalizer::FORM_D);

        // Convert special characters to their closest ASCII equivalents
        $input = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        $input = $input !== false ? $input : '';

        // Replace any non-alphanumeric characters (excluding spaces) with an empty string
        $input = preg_replace('/[^a-zA-Z0-9\s]/', '', $input);

        // Replace any whitespace characters with an underscore
        $input = preg_replace('/\s+/', '_', $input);

        return $input;
    }
}
