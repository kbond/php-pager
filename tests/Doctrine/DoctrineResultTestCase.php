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
    /** @var EntityManager */
    protected $em;

    protected function setUp()
    {
        parent::setUp();

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
            $this->em->getClassMetadata(__NAMESPACE__.'\\DoctrineOrmEntity'),
        ]);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em = null;
    }

    protected function persistEntities(int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->em->persist(new DoctrineOrmEntity());
        }

        $this->em->flush();
        $this->em->clear();
    }

    protected function getExpectedFirstValue()
    {
        return new DoctrineOrmEntity(1);
    }
}

/**
 * @Entity
 */
class DoctrineOrmEntity
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
    public $value = 'value';

    public function __construct($id = null)
    {
        $this->id = $id;
    }
}
