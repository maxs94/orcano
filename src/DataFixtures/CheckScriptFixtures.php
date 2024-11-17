<?php

namespace App\DataFixtures;

use App\Entity\CheckScript;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class CheckScriptFixtures extends Fixture implements DependentFixtureInterface
{

    private const SCRIPT_PATH = '/scripts/checks/';
    private const SCRIPT_FILE = 'ping4.sh';
    public const REFERENCE_KEY_CHECK_SCRIPT = 'ref-CheckScript-Fixtures';

    public function __construct(private readonly KernelInterface $kernel)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $checkScript = (new CheckScript())
            ->setName('Fixture Script')
            ->setFilename('Ping v4')
            ->setDescription('Ping Connection')
            ->setFilehash(md5(file_get_contents($this->kernel->getProjectDir() . self::SCRIPT_PATH . self::SCRIPT_FILE)))
        ;

        $this->addReference(self::REFERENCE_KEY_CHECK_SCRIPT, $checkScript);
        $manager->persist($checkScript);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
