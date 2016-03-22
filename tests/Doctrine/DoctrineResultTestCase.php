<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Zenstruck\Porpaginas\Tests\ResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class DoctrineResultTestCase extends ResultTestCase
{
    protected function setupEntityManager($count)
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

        for ($i = 0; $i < $count; ++$i) {
            $entityManager->persist(new DoctrineOrmEntity());
        }

        $entityManager->flush();
        $entityManager->clear();

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
