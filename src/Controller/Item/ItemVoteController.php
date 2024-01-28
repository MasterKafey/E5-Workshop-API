<?php

namespace App\Controller\Item;

use App\Entity\Item;
use App\Entity\ItemVote;
use App\Entity\ItemVoteProposition;
use App\Form\Type\Item\Vote\CreateItemVoteType;
use App\Form\Type\Item\Vote\UpdateItemVoteType;
use App\Utils\FormHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemVoteController extends AbstractController
{
    public function create(array $data, EntityManagerInterface $entityManager): Response
    {
        $item = new ItemVote();
        $form = $this->createForm(CreateItemVoteType::class, $item);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => true,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }

    public function update(Request $request, ItemVote $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateItemVoteType::class, $item);
        /** @var ItemVoteProposition[] $oldPropositions */
        $oldPropositions = $item->getPropositions();
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => true,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $newPropositions = new ArrayCollection();
        foreach ($form->get('propositions') as $propositionForm) {
            $newProposition = null;
            if ($propositionForm->get('id')->getData() !== null) {
                foreach ($oldPropositions as $oldProposition) {
                    if ($propositionForm->get('id')->getData() === $oldProposition->getId()) {
                        $newProposition = $oldProposition;
                        break;
                    }
                }
            }

            if (null === $newProposition) {
                $newProposition = (new ItemVoteProposition())->setItem($item);
            }

            $newProposition->setText($propositionForm->get('text')->getData());
            $newPropositions->add($newProposition);
        }

        $item->setPropositions($newPropositions);

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->forward(ItemController::class . '::show', ['item' => $item]);
    }
}