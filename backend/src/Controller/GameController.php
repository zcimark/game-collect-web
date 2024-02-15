<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Service\GameCollectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GameController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository, private SerializerInterface $serializer)
    {

    }
    

    #[Route('/api/list', name: 'game-collection')]
    public function list(): Response
    {
        
        
        $gameCollection = $this->gameRepository->findAll();
       
        $responseData = $this->serializer->serialize($gameCollection, 'json');
      
        return new Response($responseData);
    }

    #[Route('/api/create-game', name: 'game_create', methods: 'POST')]
    public function post(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $content = $request->request->all();

        try {
            $game = new Game();
            $form = $this->createForm(GameType::class, $game);
            $form->submit($content);
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManager->persist($game);
                $entityManager->flush();
            }

            return $this->json(['message' => 'Game successfully created for collection.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['errors' => (string) $form->getErrors(true, false)], Response::HTTP_BAD_REQUEST);
        }
    }
}
