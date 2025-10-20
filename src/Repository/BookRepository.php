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

    /**
     * Récupère les livres d’un auteur triés par date (DQL)
     */
    public function findBooksByAuthorSortDateDQL($author): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT b
             FROM App\Entity\Book b
             WHERE b.author = :author
             ORDER BY b.publishedAt DESC'
        )->setParameter('author', $author);

        return $query->getResult();
    }

    /**
     * Récupère les livres d’un auteur triés par date (QueryBuilder)
     */
    public function findBooksByAuthorSortDateQB($author): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.author = :author')
            ->setParameter('author', $author)
            ->orderBy('b.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
