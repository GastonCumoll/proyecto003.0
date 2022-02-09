<?php

namespace App\Tests;

use App\Controller\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testSuma(): void
    {
        $calc = new Calculator();
        $result = $calc->suma(30, 12);
        $band=false;
        if($result==42){
            $band=true;
        }
        $this->assertTrue($band);
    }
    public function testResta(): void
    {
        $calc = new Calculator();
        $result = $calc->resta(30, 12);
        $band=false;
        if($result==18){
            $band=true;
        }
        $this->assertTrue($band);
    }
    public function testDivision(): void
    {
        $calc = new Calculator();
        $result = $calc->division(10, 5);
        $band=false;
        if($result==2){
            $band=true;
        }
        $this->assertTrue($band);
    }
    public function testMult(): void
    {
        $calc = new Calculator();
        $result = $calc->mult(30, 12);
        $band=false;
        if($result==360){
            $band=true;
        }
        $this->assertTrue($band);
    }
}
