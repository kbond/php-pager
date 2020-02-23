<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait HasEntityManager
{
    /** @var EntityManager */
    protected $em;

    /**
     * @before
     */
    protected function setupEntityManager()
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
    protected function teardownEntityManager()
    {
        $this->em = null;
    }
}

/**
 * @Entity
 */
class ORMEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string")
     */
    public $value;

    public function __construct(string $value, int $id = null)
    {
        $this->id = $id;
        $this->value = $value;
    }
}
