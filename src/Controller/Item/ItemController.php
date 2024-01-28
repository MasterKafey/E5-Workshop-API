<?php

namespace App\Controller\Item;

use App\Entity\Item;
use App\Entity\ItemArticle;
use App\Entity\ItemCategory;
use App\Entity\ItemMedia;
use App\Entity\ItemURL;
use App\Entity\ItemVote;
use App\Entity\Screen;
use App\Form\Model\Item\ListItemModel;
use App\Form\Type\Item\ListItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route(path: '/item')]
class ItemController extends AbstractController
{
    private const CONTROLLERS = [
        ItemArticle::class => ItemArticleController::class,
        ItemCategory::class => ItemCategoryController::class,
        ItemMedia::class => ItemMediaController::class,
        ItemURL::class => ItemURLController::class,
        ItemVote::class => ItemVoteController::class,

        'article' => ItemArticleController::class,
        'category' => ItemCategoryController::class,
        'media' => ItemMediaController::class,
        'url' => ItemURLController::class,
        'vote' => ItemVoteController::class,
    ];

    #[IsGranted('ROLE_USER')]
    #[Route('/create/{id}', methods: [Request::METHOD_POST])]
    public function create(Request $request, Screen $screen): Response
    {
        $data = $request->request->all();
        try {
            $data = [...$data, ...$request->toArray()];
        } catch (\Exception) {
        }
        $type = $data['type'] ?? null;

        if ($type === null) {
            throw $this->createNotFoundException("Argument named 'type' is missing");
        }

        $controller = self::CONTROLLERS[$type] ?? null;

        if ($controller === null) {
            throw $this->createNotFoundException("Item with type '$type' doesn't exist");
        }

        unset($data['type']);

        return $this->forward($controller . '::create', ['data' => $data, 'screen' => $screen]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/update', methods: [Request::METHOD_PUT])]
    public function update(Item $item): Response
    {
        return $this->forward(self::CONTROLLERS[get_class($item)] . '::update', ['item' => $item]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{itemId}/delete-from-screen/{screenId}', methods: [Request::METHOD_DELETE])]
    public function deleteFromScreen(int $itemId, int $screenId, EntityManagerInterface $entityManager): JsonResponse
    {
        $item = $entityManager->getRepository(Item::class)->find($itemId);

        if (null === $item) {
            throw new NotFoundHttpException("Item with id $itemId not found");
        }

        $screen = $entityManager->getRepository(Screen::class)->find($screenId);

        if (null === $screen) {
            throw new NotFoundHttpException("Screen with id $screenId not found");
        }

        $screen->removeItem($item);
        $entityManager->flush();

        return $this->json([
            'result' => true,
        ]);
    }

    #[Route('/{id}', methods: [Request::METHOD_GET])]
    public function show(Item $item, ObjectNormalizer $normalizer): Response
    {

        return $this->json([
            'result' => true,
            'data' => $normalizer->normalize($item),
        ]);
    }

    #[Route(path: '/', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    public function list(Request $request, EntityManagerInterface $entityManager, ObjectNormalizer $normalizer): JsonResponse
    {
        $model = new ListItemModel();
        $form = $this->createForm(ListItemType::class, $model);
        $form->submit($request->toArray());

        $result = $entityManager->getRepository(Item::class)->search($model);
        $data = array_map(function ($entity) use ($normalizer) {
            return $normalizer->normalize($entity);
        }, $result);

        return $this->json([
            'result' => true,
            'data' => $data,
        ]);

    }
}