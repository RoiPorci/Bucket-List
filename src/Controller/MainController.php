<?php


namespace App\Controller;


use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    /**
     * @Route ("/", name="main_home")
     */
    public function home(WishRepository $wishRepository): Response
    {
        $wishesLast10 = $wishRepository->findBy(['is_published' => 1], ['date_created' => 'DESC'], 10);
        return $this->render("main/home.html.twig", [
            'wishes' => $wishesLast10
        ]);
    }

    /**
     * @Route ("/about-us", name="main_about_us")
     */
    public function aboutUs() : Response
    {
        return $this->render("main/about_us.html.twig");
    }
}