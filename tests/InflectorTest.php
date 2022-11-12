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
        $this->assertSame(
            'hat',
            Inflector::inflect('hat', 1)
        );
    }

    public function testInflectPlural(): void
    {
        $this->assertSame(
            'hats',
            Inflector::inflect('hat', 2)
        );
    }

    public function testPluralize(): void
    {
        $this->assertSame(
            'countries',
            Inflector::pluralize('country')
        );
    }

    public function testPluralizeTitle(): void
    {
        $this->assertSame(
            'Countries',
            Inflector::pluralize('Country')
        );
    }

    public function testPluralizeIrregular(): void
    {
        $this->assertSame(
            'people',
            Inflector::pluralize('person')
        );
    }

    public function testPluralizeIrregularTitle(): void
    {
        $this->assertSame(
            'People',
            Inflector::pluralize('Person')
        );
    }

    public function testPluralizeUncountable(): void
    {
        $this->assertSame(
            'sheep',
            Inflector::pluralize('sheep')
        );
    }

    public function testPluralizeUncountableTitle(): void
    {
        $this->assertSame(
            'Sheep',
            Inflector::pluralize('Sheep')
        );
    }

    public function testSingularize(): void
    {
        $this->assertSame(
            'country',
            Inflector::singularize('countries')
        );
    }

    public function testSingularizeTitle(): void
    {
        $this->assertSame(
            'Country',
            Inflector::singularize('Countries')
        );
    }

    public function testSingularizeIrregular(): void
    {
        $this->assertSame(
            'person',
            Inflector::singularize('people')
        );
    }

    public function testSingularizeIrregularTitle(): void
    {
        $this->assertSame(
            'Person',
            Inflector::singularize('People')
        );
    }

    public function testSingularizeUncountable(): void
    {
        $this->assertSame(
            'sheep',
            Inflector::singularize('sheep')
        );
    }

    public function testSingularizeUncountableTitle(): void
    {
        $this->assertSame(
            'Sheep',
            Inflector::singularize('Sheep')
        );
    }

}
