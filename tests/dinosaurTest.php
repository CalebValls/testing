<?php

namespace App\Tests;

use App\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Test\TestSize\Medium;

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
        self::assertSame("large", $dino->getSizeDescription(), 'Expected dinosaur to be large');
    }

    public function testDinoBetween5And9MetersIsMedium(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty', //cool dude
            genus: 'Tyrannosaurus',
            length: 6,
            enclosure: 'Paddock C',
        );
        self::assertSame('medium', $dino->getSizeDescription(), 'Expected dinosaur to be medium');
    } 

    public function testDinoLessThan5MetersIsSmall(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty', //cool dude
            genus: 'Tyrannosaurus',
            length: 4,
            enclosure: 'Paddock C',
        );
        self::assertSame('small', $dino->getSizeDescription(), 'Expected dinosaur to be small');
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
