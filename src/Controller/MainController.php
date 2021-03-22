<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
     * @Route()
     */
    public function home(){
        echo "<h1>Accueil</h1>";
        die();
    }
}