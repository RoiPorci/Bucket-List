<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes/{page}", name="wish_list", requirements={"page": "\d+"})
     */
    public function list(int $page = 1, WishRepository $wishRepository): Response
    {
        $maxResults = 20;
        $results = $wishRepository->findWishList($page, $maxResults);
        $wishesList = $results['result'];
        $totalWishes = $results['resultCount'];
        $totalPages = ceil($totalWishes/$maxResults);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishesList,
            'currentPage' => $page,
            'totalWishes' => $totalWishes,
            'totalPages' => $totalPages
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
