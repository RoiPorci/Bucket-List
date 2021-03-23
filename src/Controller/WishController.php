<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes", name="wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wishesLast10 = $wishRepository->findBy([], ['date_created' => 'DESC'], 10);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishesLast10
        ]);
    }

    /**
     * @Route("/wishes/detail/{id}", name="wish_detail")
     */
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if(!$wish){
            throw $this->createNotFoundException("Oups, ce voeu n'existe pas !");
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);
    }
}
