<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Zenstruck\Porpaginas\Doctrine\ORM\ORMQueryResult;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

class ORMQueryResultTest extends ResultTestCase
{
    protected function createResultWithItems($count)
    {
        $entityManager = $this->setupEntityManager();

        for ($i = 0; $i < $count; ++$i) {
            $entityManager->persist(new DoctrineOrmEntity());
        }
        $entityManager->flush();
        $entityManager->clear();

        $query = $entityManager->createQuery('SELECT e FROM Zenstruck\Porpaginas\Tests\Doctrine\ORM\DoctrineOrmEntity e');

        return new ORMQueryResult($query);
    }

    private function setupEntityManager()
    {
        $paths = [];
        $isDevMode = false;

        // the connection configuration
        $dbParams = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $entityManager = EntityManager::create($dbParams, $config);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema([
            $entityManager->getClassMetadata(__NAMESPACE__.'\\DoctrineOrmEntity'),
        ]);

        return $entityManager;
    }
}

/**
 * @Entity
 */
class DoctrineOrmEntity
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;
}
