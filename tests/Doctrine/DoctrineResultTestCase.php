<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine;

use Zenstruck\Porpaginas\Tests\ResultTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class DoctrineResultTestCase extends ResultTestCase
{
    use HasEntityManager;

    protected function persistEntities(int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->em->persist(new ORMEntity('value '.($i + 1)));
        }

        $this->em->flush();
        $this->em->clear();
    }
}
