<?php

namespace App\Controller\Item;

use App\Entity\ItemCategory;
use App\Entity\Screen;
use App\Form\Type\Item\Category\CreateItemCategoryType;
use App\Form\Type\Item\Category\UpdateItemCategoryType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemCategoryController extends AbstractController
{
    public function create(array $data, Screen $screen, EntityManagerInterface $entityManager): Response
    {
        $item = new ItemCategory();
        $item->addScreen($screen);
        $form = $this->createForm(CreateItemCategoryType::class, $item);
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

    public function update(Request $request, ItemCategory $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateItemCategoryType::class, $item);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => true,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }
}