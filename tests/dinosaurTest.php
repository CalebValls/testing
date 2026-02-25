<?php
namespace App\Tests;
use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;
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

    public function testItWorksDinosaurIs10MetersOrLonger(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty', //cool dude
            genus: 'Tyrannosaurus',
            length: 10,
            enclosure: 'Paddock C',
        );
        self::assertGreaterThanOrEqual(10, $dino->getSizeDescription());

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
}
