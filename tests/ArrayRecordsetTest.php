<?php

namespace R2\Helpers\Tests;

use PHPUnit\Framework\TestCase;
use R2\Helpers\ArrayRecordset;

class ArrayRecordsetTest extends TestCase
{
    protected $nameAge = [
        ['name' => 'Alfred', 'age' => 40],
        ['name' => 'Mark',   'age' => 40],
        ['name' => 'Lue',    'age' => 45],
        ['name' => 'Ameli',  'age' => 38],
        ['name' => 'Barb',   'age' => 38],
    ];
    protected $alfaAbba = [
        ['f' => 'alfa'],
        ['f' => 'Abba'],
        ['f' => 'Beatles'],
        ['f' => 'beta'],
    ];

    public function testBasicSorting()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->orderBy('age', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $this->assertEquals(
            [
                ['name' => 'Lue',    'age' => 45],
                ['name' => 'Alfred', 'age' => 40],
                ['name' => 'Mark',   'age' => 40],
                ['name' => 'Ameli',  'age' => 38],
                ['name' => 'Barb',   'age' => 38],
            ],
            $result
        );
    }

    public function testFirst()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->orderBy('age', 'desc')
            ->orderBy('name', 'asc')
            ->first();

        $this->assertEquals(['name' => 'Lue', 'age' => 45], $result);
    }

    public function testValue()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->orderBy('age', 'desc')
            ->orderBy('name', 'asc')
            ->value('name');

        $this->assertEquals('Lue', $result);
    }

    public function testSortingMaintainKeys()
    {
        $result = (new ArrayRecordset($this->nameAge, ArrayRecordset::PRESERVE_KEYS))
            ->orderBy('age', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $this->assertEquals(
            [
                2 => ['name' => 'Lue',    'age' => 45],
                0 => ['name' => 'Alfred', 'age' => 40],
                1 => ['name' => 'Mark',   'age' => 40],
                3 => ['name' => 'Ameli',  'age' => 38],
                4 => ['name' => 'Barb',   'age' => 38],
            ],
            $result
        );
    }

    public function testSortCaseSensitive()
    {
        $result = (new ArrayRecordset($this->alfaAbba, ArrayRecordset::CASE_SENSITIVE))
            ->orderBy('f', 'asc')
            ->get();

        $this->assertEquals(
            [
                ['f' => 'Abba'],
                ['f' => 'Beatles'],
                ['f' => 'alfa'],
                ['f' => 'beta'],
            ],
            $result
        );
    }

    public function testSortCaseInsensitive()
    {
        $result = (new ArrayRecordset($this->alfaAbba))
            ->orderBy('f', 'asc')
            ->get();

        $this->assertEquals(
            [
                ['f' => 'Abba'],
                ['f' => 'alfa'],
                ['f' => 'Beatles'],
                ['f' => 'beta'],
            ],
            $result
        );
    }

    public function testPluck()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->pluck('name');

        $this->assertEquals(['Alfred', 'Mark', 'Lue', 'Ameli', 'Barb'], $result);
    }

    public function testPluckKeys()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->pluck('age', 'name');

        $this->assertEquals(['Alfred' => 40, 'Mark' => 40, 'Lue' => 45, 'Ameli' => 38, 'Barb' => 38], $result);
    }

    public function testSortAndPluck()
    {
        $result = (new ArrayRecordset($this->nameAge))
            ->orderBy('age', 'desc')
            ->orderBy('name', 'asc')
            ->pluck('name');

        $this->assertEquals(['Lue', 'Alfred', 'Mark', 'Ameli', 'Barb'], $result);
    }
}
