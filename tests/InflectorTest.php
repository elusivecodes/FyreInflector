<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Utility\Inflector,
    PHPUnit\Framework\TestCase;

final class InflectorTest extends TestCase
{

    public function testInflectSingular(): void
    {
        $this->assertEquals(
            'hat',
            Inflector::inflect('hat', 1)
        );
    }

    public function testInflectPlural(): void
    {
        $this->assertEquals(
            'hats',
            Inflector::inflect('hat', 2)
        );
    }

    public function testPluralize(): void
    {
        $this->assertEquals(
            'countries',
            Inflector::pluralize('country')
        );
    }

    public function testPluralizeIrregular(): void
    {
        $this->assertEquals(
            'people',
            Inflector::pluralize('person')
        );
    }

    public function testPluralizeUncountable(): void
    {
        $this->assertEquals(
            'sheep',
            Inflector::pluralize('sheep')
        );
    }

    public function testSingularize(): void
    {
        $this->assertEquals(
            'country',
            Inflector::singularize('countries')
        );
    }

    public function testSingularizeIrregular(): void
    {
        $this->assertEquals(
            'person',
            Inflector::singularize('people')
        );
    }

    public function testSingularizeUncountable(): void
    {
        $this->assertEquals(
            'sheep',
            Inflector::singularize('sheep')
        );
    }

}
