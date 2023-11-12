<?PHP 
declare(strict_types=1);

namespace App\Service\Api;

use App\Entity\ApiEntityInterface;
use App\Event\EntityUpsertEvent;
use App\Exception\EntityNotFoundException;
use App\Exception\RepositoryNotFoundException;
use App\Repository\AbstractServiceEntityRepository;
use App\Service\Converter\CaseConverter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class EntityUpsertService 
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger
    ) { }

    /** @param array<string, mixed> $data */
    public function upsert(string $entityName, array $data): void 
    {
        $repository = $this->getEntityRepository($entityName);

        $entityClassName = $repository->getClassName();

        /** @var ApiEntityInterface $entity */
        $entity = isset($data['id']) ? $repository->find($data['id']) : new $entityClassName();

        if (!$entity instanceof ApiEntityInterface) {
            throw new \Exception('Entity does not implement ApiEntityInterface');
        }

        $data = $this->hydrateEntities($data, $entity);

        if (!method_exists($entity, 'setData')) {
            throw new \Exception('Method setData not found on entity');
        }

        $entity->setData($data);

        $this->em->persist($entity);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new EntityUpsertEvent($entity));
    }
    
    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function hydrateEntities(array $data, ApiEntityInterface $entity): array
    {
        $ret = [];

        foreach ($data as $key => $value) {
            $key = CaseConverter::kebabCaseToCamelCase($key);

            $reflectionClass = new \ReflectionClass($entity);

            if ($reflectionClass->hasProperty($key) === false) {
                $this->logger->warning("Property {$key} not found on entity {$reflectionClass->getName()}");
                $ret[$key] = $value;
                continue;
            }

            $reflectionProperty = $reflectionClass->getProperty($key);

            /** @var \ReflectionNamedType $reflectionType */
            $reflectionType = $reflectionProperty->getType();
            $type = $reflectionType->getName();

            // if type is an object, try to get the related entity from database
            if (stristr($type, '\\')) {
                /** @phpstan-ignore-next-line */
                $repo = $this->em->getRepository($type);
                if ($repo == null) {
                    throw new RepositoryNotFoundException("Repository {$type} not found");
                }

                $relatedEntity = $repo->find((int) $value);
                if ($relatedEntity === null) {
                    throw new EntityNotFoundException("Entity {$type} with id {$value} not found");
                }
                $ret[$key] = $relatedEntity;
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    private function getEntityRepository(string $entity): AbstractServiceEntityRepository
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
