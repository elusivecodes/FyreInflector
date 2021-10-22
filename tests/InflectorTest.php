<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Inflector,
    PHPUnit\Framework\TestCase;

final class InflectorTest extends TestCase
{

    public function testPluralize(): void
    {
        $this->assertEquals(
            'hats',
            Inflector::pluralize('hat')
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
            'hat',
            Inflector::singularize('hats')
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
