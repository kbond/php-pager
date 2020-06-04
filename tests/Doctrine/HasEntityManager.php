<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Zenstruck\Porpaginas\Tests\Doctrine\Fixtures\ORMEntity;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait HasEntityManager
{
    protected ?EntityManager $em = null;

    /**
     * @before
     */
    protected function setupEntityManager(): void
    {
        $paths = [];
        $isDevMode = false;

        // the connection configuration
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->em = EntityManager::create($dbParams, $config);

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema([
            $this->em->getClassMetadata(ORMEntity::class),
        ]);
    }

    /**
     * @after
     */
    protected function teardownEntityManager(): void
    {
        $this->em = null;
    }

    protected function persistEntities(int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->em->persist(new ORMEntity('value '.($i + 1)));
        }

        $this->em->flush();
        $this->em->clear();
    }
}
