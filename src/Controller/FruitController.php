<?php

namespace App\Controller;

use App\Entity\Fruit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class FruitController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function index(Request $request): JsonResponse
    {
        $currentPage = $request->toArray()['currentPage'] ?? 1;
        $rowsPerPage = $request->toArray()['rowsPerPage'] ?? 0;
        $name = $request->toArray()['name'] ?? '';
        $family = $request->toArray()['family'] ?? '';


        $query = $this->entityManager->getRepository(Fruit::class)->createQueryBuilder('e');

        if ($name) {
            $query = $query->orWhere('e.name LIKE :q')->setParameter('q', "%{$name}%");
        }

        if ($family) {
            $query = $query->orWhere('e.family LIKE :q')->setParameter('q', "%{$family}%");
        }

        $pagination = $this->paginator->paginate(
            $query,
            $currentPage,
            $rowsPerPage,
        );

        $total = $this->entityManager->getRepository(Fruit::class)->findAll();
        $favourites = $this->entityManager->getRepository(Fruit::class)->findBy(array('favourite' => true));

        return $this->json([
            'fruits' => $pagination->getItems(),
            'total' => count($total),
            'favourites' => $favourites,
        ], Response::HTTP_OK);
    }

    public function addFavourite(Request $request): JsonResponse
    {
        $id = $request->toArray()['id'];
        $status = $request->toArray()['status'];

        $fruit = $this->entityManager->getRepository(Fruit::class)->find($id);

        if (!$fruit) {
            throw new Exception('Invalid fruit id ' . $id);
        }

        $fruit->setFavourite($status);
        $this->entityManager->persist($fruit);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_OK);
    }

    public function getFavourites(): JsonResponse
    {
        $fruits = $this->entityManager->getRepository(Fruit::class)->findBy(array('favourite' => true));

        return $this->json($fruits, Response::HTTP_OK);
    }
}
