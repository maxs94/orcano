<?PHP 
declare(strict_types=1);

namespace App\Controller\Page;

use App\Context\Context;
use App\DataObject\Page\PageMessageDataObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AbstractPageController extends AbstractController
{
    public function __construct(protected Context $context) {}

    /** @var array<string|int, PageMessageDataObject> */
    private array $errors = [];

    /** @var array<string|int, PageMessageDataObject> */
    private array $messages = [];

    /** @param array<string, mixed> $parameters */
    protected function renderPage(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['context'] = $this->context;
        $parameters['errors'] = $this->errors;
        $parameters['messages'] = $this->messages;

        $response = $this->render($view, $parameters, $response);

        $this->messages = [];

        return $response;
    }

    /** @return array<string|int, PageMessageDataObject> */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $text, ?string $key = null): void
    {
        $message = new PageMessageDataObject($text, PageMessageDataObject::TYPE_DANGER);

        if ($key !== null) {
            $this->errors[$key] = $message;
            return;
        }

        $this->errors[] = $message;
    }

    /** @param array<string|int, PageMessageDataObject> $errors */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
    
    /** @return array<string, PageMessageDataObject> */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(string $text, ?string $type = 'info', ?string $key = null): void
    {
        $message = new PageMessageDataObject($text, $type);

        if ($key !== null) {
            $this->messages[$key] = $message;
            return;
        }

        $this->messages[] = $message;
    }

    /** @param array<string|int, PageMessageDataObject> $messages */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }
}
