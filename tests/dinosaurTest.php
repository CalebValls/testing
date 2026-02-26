<?php

namespace App\Tests;

use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Test\TestSize\Medium;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Enum\HealthStatus;

class DinosaurTest extends TestCase
{
    public function testItWorksEquals(): void // ==
    {
        self::assertEquals('42', 42);
    }
    public function testItWorksSame(): void // === 
    {
        self::assertSame(42, 42);
    }
    #[DataProvider('sizeDescriptionProvider')]
    public function testSizeDescriptiomFromDinoIsCorrect(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty', //cool dude
            genus: 'Tyrannosaurus',
            length: $length,
            enclosure: 'Paddock C',
        );
        self::assertSame($expectedSize, $dino->getSizeDescription(), "Expected dinosaur to be $expectedSize");
    }

    public function testCanGetData(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock C',
        );
        self::assertSame('Big Eaty', $dino->getName());
        self::assertSame('Tyrannosaurus', $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame('Paddock C', $dino->getEnclosure());
    }

    public static function sizeDescriptionProvider(): \Generator
    {
        yield '15 size' => [15, 'large'];
        yield '10 size' => [10, 'large'];
        yield '9 size' => [9, 'medium'];
        yield '5 size' => [5, 'medium'];
        yield '4 size' => [4, 'small'];
    }

    public function testAcceptVisitorsByDefault(): void
    {
        $dino = new Dinosaur(name: 'Dennis');
        self::assertTrue($dino->isAcceptingVisitors());
    }

    public function testIsNotAcceptingVisitorsIfSick(): void
    {
        // $this->markTestIncomplete('This test is not implemented yet');
        $dino = new Dinosaur(name: 'Bumpy');
        $dino->setHealth(HealthStatus::SICK);

        self::assertFalse($dino->isAcceptingVisitors());
    }
}
