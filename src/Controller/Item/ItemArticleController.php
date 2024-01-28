<?php

namespace App\Controller\Item;

use App\Entity\ItemArticle;
use App\Entity\Screen;
use App\Form\Type\Item\Article\CreateItemArticleType;
use App\Form\Type\Item\Article\UpdateItemArticleType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemArticleController extends AbstractController
{
    public function create(array $data, Screen $screen, EntityManagerInterface $entityManager): Response
    {
        $item = new ItemArticle();
        $item->addScreen($screen);
        $form = $this->createForm(CreateItemArticleType::class, $item);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }

    public function update(Request $request, ItemArticle $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateItemArticleType::class, $item);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }
}