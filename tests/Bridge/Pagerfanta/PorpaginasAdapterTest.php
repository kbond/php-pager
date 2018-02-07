<?php

namespace Zenstruck\Porpaginas\Tests\Bridge\Pagerfanta;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\TestCase;
use Zenstruck\Porpaginas\Arrays\ArrayResult;
use Zenstruck\Porpaginas\Bridge\Pagerfanta\PorpaginasAdapter;

class PorpaginasAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function it_counts_total_number_of_results()
    {
        $pagerfanta = new Pagerfanta(
            new PorpaginasAdapter(
                new ArrayResult([1, 2, 3, 4])
            )
        );

        $this->assertEquals(4, $pagerfanta->getNbResults());
    }

    /**
     * @test
     */
    public function it_iterates_slice()
    {
        $pagerfanta = new Pagerfanta(
            new PorpaginasAdapter(
                new ArrayResult([1, 2, 3, 4])
            )
        );

        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage(1);

        $this->assertEquals([1, 2], \iterator_to_array($pagerfanta->getCurrentPageResults()));

        $pagerfanta->setCurrentPage(2);

        $this->assertEquals([3, 4], \iterator_to_array($pagerfanta->getCurrentPageResults()));
    }
}
