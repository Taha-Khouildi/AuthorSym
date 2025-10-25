<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
     public function findByTitle($value): array
       {
           $query=$this->getEntityManager()
           ->createQuery('Select a From App\Entity\Book a WHERE a.title=:title')
           ->setParameter('title',$value);
           return $query->getResult();
       }
      public function countByCategory(string $category): int
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT COUNT(b.id) 
         FROM App\Entity\Book b 
         WHERE b.category = :category'
    )->setParameter('category', $category);

    return (int) $query->getSingleScalarResult();
}

public function countByPublished(bool $published): int
{
    return (int) $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.Published = :published')
        ->setParameter('published', $published)
        ->getQuery()
        ->getSingleScalarResult();
}
public function findPublishedBetween(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT b
         FROM App\Entity\Book b
         WHERE b.Published = true
         AND b.PublishDate BETWEEN :start AND :end
         ORDER BY b.PublishDate ASC'
    )
    ->setParameter('start', $startDate)
    ->setParameter('end', $endDate);

    return $query->getResult();
}




}
