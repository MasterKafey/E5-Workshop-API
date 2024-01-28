<?php

namespace App\Controller\Item;

use App\Business\FileBusiness;
use App\Entity\ItemMedia;
use App\Entity\Screen;
use App\Form\Model\Item\Media\CreateItemMediaModel;
use App\Form\Type\Item\Media\CreateItemMediaType;
use App\Form\Type\Item\Media\UpdateItemMediaType;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemMediaController extends AbstractController
{
    public function create(Request $request, array $data, Screen $screen, FileBusiness $fileBusiness, EntityManagerInterface $entityManager): Response
    {
        $model = new CreateItemMediaModel();
        $form = $this->createForm(CreateItemMediaType::class, $model);
        $form->submit(['file' => $request->files->get('file'), ...$data]);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $item = new ItemMedia();
        $item
            ->setName($model->getName())
            ->setIcon($model->getIcon())
            ->setFile($fileBusiness->uploadFile($model->getFile()))
            ->addScreen($screen);

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }

    public function update(ItemMedia $item, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateItemMediaType::class, $item);
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