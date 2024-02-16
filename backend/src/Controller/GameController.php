<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Service\FormErrorService;
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
    public function __construct(
        GameRepository $gameRepository,
        SerializerInterface $serializer,
        FormErrorService $formErrorService
    )
    {
        $this->serializer = $serializer;
        $this->gameRepository = $gameRepository;
        $this->formErrorService = $formErrorService;
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
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);

        try {
    
            $form->submit($content);
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManager->persist($game);
                $entityManager->flush();

            }
            return $this->json(['message' => 'Game successfully created for collection.'], Response::HTTP_CREATED);


        } catch (\Exception $e) {
            $errors = $this->formErrorService->generateErrorsArrayFromForm($form);
            dd($errors);
            $errors['errors'] = true;
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/game/{id}', name: 'game_show', methods: 'GET')]
    public function show(EntityManagerInterface $entityManager, $id): Response
    {
       $game = $entityManager->getRepository(Game::class)->find($id);

       if (!$game) {
            return $this->json("No game found for id ' . $id, 404");
       }

       $responseData = $this->serializer->serialize($game, 'json');

       return new Response($responseData);
    }


    #[Route('/api/edit-game/{id}', name: 'game_edit', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $content = $request->request->all();
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            return $this->json("No game found for id ' . $id, 404");
       }

       try {
            $form = $this->createForm(GameType::class, $game);
            $form->submit($content);
            if ($form->isSubmitted() && $form->isValid()) { 
                $entityManager->persist($game);
                $entityManager->flush();
            }
            
            return $this->json(['message' => "Game successfully updated", Response::HTTP_OK]);
       } catch (\Exception $e) {
            return $this->json(['errors' => (string) $form->getErrors(true, false)], Response::HTTP_BAD_REQUEST);
       }
    }

    #[Route('/api/delete-game/{id}', name: 'game_delete', methods: 'DELETE')]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            $game = $entityManager->getRepository(Game::class)->find($id);
            $entityManager->remove($game);
            $entityManager->flush();

            return $this->json(['message' => 'Game successfully deleted from collection.'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['errors' => 'Unable to delete game'], Response::HTTP_BAD_REQUEST);
        }
    }
}
