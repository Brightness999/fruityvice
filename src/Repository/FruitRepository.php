<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;


/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
  private EntityManagerInterface $entityManager;

  public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
    parent::__construct($registry, Fruit::class);
  }

  public function add(): void
  {
    $client = HttpClient::create();
    $response = $client->request('GET', 'https://fruityvice.com/api/fruit/all');
    $statusCode = $response->getStatusCode();

    if ($statusCode === 200) {
      $content = $response->toArray();
      $count = 0;
      foreach ($content as $data) {
        $isExist = $this->entityManager->getRepository(Fruit::class)->find($data['id']);
        if (!$isExist) {
          $count++;
          $fruit = new Fruit();
          $fruit->setFruit($data);
          $this->entityManager->persist($fruit);
          $this->entityManager->flush();
        }
      }

      if ($count > 0) {
        $transport = Transport::fromDsn('%env(MAILER_DSN)%');
        $mailer = new Mailer($transport);

        $email = (new Email())
          ->from('admin@fruityvice.com')
          ->to('client@fruityvice.com')
          ->subject('New Fruit')
          ->text('New fruits have just added!');

        $mailer->send($email);
      }
    }
  }

  public function remove(Fruit $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }
}
