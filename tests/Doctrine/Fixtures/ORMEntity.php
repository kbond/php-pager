<?php

namespace Zenstruck\Porpaginas\Tests\Doctrine\Fixtures;

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
