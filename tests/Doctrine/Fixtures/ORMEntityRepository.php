<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Zenstruck\Porpaginas\Doctrine\Repository\ORMRepository;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class ORMEntityRepository extends ORMRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClassName(): string
    {
        return ORMEntity::class;
    }

    protected function em(): EntityManagerInterface
    {
        return $this->em;
    }
}
