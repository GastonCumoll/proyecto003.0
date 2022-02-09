<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Calculator
{
    public function suma($a, $b)
    {
        return $a + $b;
    }
    public function resta($a, $b)
    {
        return $a - $b;
    }
    public function division($a, $b)
    {   
        if($b != 0){
            return $a / $b;
        }
        
        
    }
    public function mult($a, $b)
    {
        return $a * $b;
    }
}
