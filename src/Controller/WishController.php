<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wishes/{page}", name="wish_list", requirements={"page": "\d+"})
     * @param int $page
     * @param WishRepository $wishRepository
     * @return Response
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
     * @param int $id
     * @param WishRepository $wishRepository
     * @return Response
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

    /**
     * @Route("/wish/new/", name="wish_new")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish->setLikes(0);
            $wish->setIsPublished(1);
            $wish->setDateCreated(new \DateTime());

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash("success", "Merci d'avoir fait votre voeu!");

            return $this->render("wish/detail.html.twig", [
                'id' => $wish->getId(),
                'wish' => $wish
            ]);
        }

        return $this->render('wish/new.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }
}
