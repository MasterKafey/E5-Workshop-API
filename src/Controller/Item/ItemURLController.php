<?php

namespace App\Controller\Item;

use App\Entity\ItemURL;
use App\Entity\Screen;
use App\Form\Type\Item\URL\CreateItemURLType;
use App\Form\Type\Item\URL\UpdateItemURLType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemURLController extends AbstractController
{
    public function create(array $data, Screen $screen, EntityManagerInterface $entityManager): Response
    {
        $item = new ItemURL();
        $item->addScreen($screen);
        $form = $this->createForm(CreateItemURLType::class, $item);
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

    public function update(ItemURL $item, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateItemURLType::class, $item);
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