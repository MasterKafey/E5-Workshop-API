<?php

namespace App\Controller;

use App\Entity\Screen;
use App\Form\Type\Screen\CreateScreenType;
use App\Form\Type\Screen\UpdateScreenType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/screen')]
class ScreenController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route(path: '/create', name: 'app_screen_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $screen = new Screen();
        $form = $this->createForm(CreateScreenType::class, $screen);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->persist($screen);
        $entityManager->flush();

        return $this->forward(self::class . '::show', ['screen' => $screen]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/', name: 'app_screen_list')]
    public function list(EntityManagerInterface $entityManager, Serializer $serializer): JsonResponse
    {
        $screens = $entityManager->getRepository(Screen::class)->findAll();

        return $this->json([
            'result' => true,
            'data' => array_map(function (Screen $screen) use ($serializer) {
                return $serializer->normalize($screen);
            }, $screens),
        ]);
    }

    #[Route(path: '/{qrCodeKey}', name: 'app_screen_show', methods: [Request::METHOD_GET])]
    public function show(Screen $screen, Serializer $serializer): JsonResponse
    {
        return $this->json([
            'result' => true,
            'data' => $serializer->normalize($screen),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/{id}/update', name: 'app_screen_update', methods: [Request::METHOD_PUT])]
    public function update(Request $request, Screen $screen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateScreenType::class, $screen);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form)
            ]);
        }

        $entityManager->flush();

        return $this->forward(self::class . '::show', ['screen' => $screen]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route(path: '/{id}/delete', name: 'app_screen_delete', methods: [Request::METHOD_DELETE])]
    public function delete(EntityManagerInterface $entityManager, Screen $screen): JsonResponse
    {
        $entityManager->remove($screen);
        $entityManager->flush();

        return $this->json([
            'result' => true,
        ]);
    }
}