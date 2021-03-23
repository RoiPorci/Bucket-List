<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    /**
     * @Route ("/", name="main_home")
     */
    public function home(): Response
    {
        return $this->render("main/home.html.twig");
    }

    /**
     * @Route ("/about-us", name="main_about-us")
     */
    public function aboutUs() : Response
    {
        return $this->render("main/about-us.html.twig");
    }
}