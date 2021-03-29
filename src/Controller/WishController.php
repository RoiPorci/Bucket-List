<?php

namespace App\Controller;

use App\Entity\Reaction;
use App\Entity\Wish;
use App\Form\ReactionType;
use App\Form\WishType;
use App\Repository\ReactionRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
        $route = "wish_list";

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishesList,
            'currentPage' => $page,
            'totalWishes' => $totalWishes,
            'totalPages' => $totalPages,
            'route' => $route
        ]);
    }

    /**
     * @Route("/wishes/detail/{id}/{page}", name="wish_detail", requirements={"page": "\d+"})
     * @param int $id
     * @param int $page
     * @param WishRepository $wishRepository
     * @param ReactionRepository $reactionRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function detail(
        int $id, int $page = 1,
        WishRepository $wishRepository,
        ReactionRepository $reactionRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response
    {
        $maxReactions = 10;

        $wish = $wishRepository->findDetailedWish($id);

        if(!$wish){
            throw $this->createNotFoundException("Oups, ce voeu n'existe pas !");
        }

        $resultsReaction = $reactionRepository->findWishReactions($wish->getId(), $page, $maxReactions);

        $reactions = $resultsReaction['result'];
        $totalReactions = $resultsReaction['resultCount'];
        $totalPages = ceil($totalReactions/$maxReactions);
        $route = "wish_detail";

        $reaction = new Reaction();
        $reactionForm = $this->createForm(ReactionType::class, $reaction);

        $reactionForm->handleRequest($request);

        if ($reactionForm->isSubmitted() && $reactionForm->isValid() && $security->isGranted('ROLE_USER')){
            $reaction->setWish($wish);
            $reaction->setDateCreated(new \DateTime());

            $entityManager->persist($reaction);
            $entityManager->flush();

            $this->addFlash("success", "Merci pour votre réaction ". $reaction->getAuthor() ."!");

            return $this->redirectToRoute('wish_detail', ['id' => $id]);

        } elseif ($reactionForm->isSubmitted() && !$reactionForm->isValid()) {
            $this->addFlash("danger", "Votre réaction n'a pas été envoyée!");
        }

        return $this->render('wish/detail.html.twig', [
            'id' => $wish->getId(),
            'wish' => $wish,
            'reactionForm' => $reactionForm->createView(),
            'reactions' => $reactions,
            'currentPage' => $page,
            'totalReactions' => $totalReactions,
            'totalPages' => $totalPages,
            'route' => $route
        ]);
    }

    /**
     * @Route("/wish/new/", name="wish_new")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();

        $username = $this->getUser()->getUsername();
        $wish->setAuthor($username);

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            $wish->setLikes(0);
            $wish->setIsPublished(1);
            $wish->setDateCreated(new \DateTime());

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash("success", "Merci d'avoir fait votre voeu ". $wish->getAuthor() ."!");

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);

        }

        return $this->render('wish/new.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }
}
