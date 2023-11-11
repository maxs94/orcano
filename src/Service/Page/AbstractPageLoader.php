<?PHP 
declare(strict_types=1);

namespace App\Service\Page;

use App\Repository\AbstractServiceEntityRepository;
use App\Service\Converter\CaseConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractPageLoader 
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly TranslatorInterface $translator
    ) {}

    protected function getEntityRepository(string $entity): AbstractServiceEntityRepository
    {
        $entityName = CaseConverter::kebabCaseToCamelCase($entity);
        $entityClassName = sprintf('App\Entity\%s', ucfirst($entityName));

        if (!class_exists($entityClassName)) {
            throw new ClassNotFoundException($entityClassName);
        }

        /** @var AbstractServiceEntityRepository $repository */
        $repository = $this->em->getRepository($entityClassName);

        return $repository;
    }
}
