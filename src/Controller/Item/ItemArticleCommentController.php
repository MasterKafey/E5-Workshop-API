<?php

namespace App\Controller\Item;

use App\Entity\ItemArticle;
use App\Entity\ItemArticleComment;
use App\Form\Type\Comment\CreateItemArticleCommentType;
use App\Form\Type\Comment\UpdateItemArticleCommentType;
use App\Security\Voter\ItemArticleCommentVoter;
use App\Utils\FormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[IsGranted('ROLE_USER')]
#[Route(path: '/comment')]
class ItemArticleCommentController extends AbstractController
{
    #[Route(path: '/create/{id}')]
    public function create(Request $request, EntityManagerInterface $entityManager, ItemArticle $article): Response
    {
        $comment = new ItemArticleComment();
        $comment->setArticle($article);

        $form = $this->createForm(CreateItemArticleCommentType::class, $comment);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->forward(self::class . '::show', ['comment' => $comment]);
    }

    #[Route(path: '/{id}/update', methods: [Request::METHOD_PUT])]
    public function update(ItemArticleComment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(ItemArticleCommentVoter::UPDATE, $comment);

        $form = $this->createForm(UpdateItemArticleCommentType::class, $comment);
        $form->submit($request->toArray());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'result' => false,
                'errors' => FormHelper::getErrors($form),
            ]);
        }

        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->forward(self::class . '::show', ['comment' => $comment]);
    }

    #[Route(path: '/{id}')]
    public function show(ItemArticleComment $comment, Serializer $serializer, ObjectNormalizer $objectNormalizer): JsonResponse
    {
        $data = $serializer->normalize($comment);
        return $this->json([
            'result' => true,
            'data' => $data,
        ]);
    }
}