<?php

namespace App\Repository;

use App\Entity\Term;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Term>
 *
 * @method Term|null find($id, $lockMode = null, $lockVersion = null)
 * @method Term|null findOneBy(array $criteria, array $orderBy = null)
 * @method Term[]    findAll()
 * @method Term[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Term::class);
    }

    /**
     * @return Term[] Returns an array of Term objects
     */
    public function findByNameField($value): array
    {
        $result = $this->createQueryBuilder('term')
            ->andWhere('term.name = :name')
            ->setParameter('name', $value)
            ->getQuery()
            ->getOneOrNullResult();

        if ($result) {
            return [
                'id' => $result->getId(),
                'name' => $result->getName(),
                'total_count' => $result->getTotalCount()
            ];
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getSumByTotalCount(): array
    {
        $result = $this->createQueryBuilder('term')
            ->select('SUM(term.total_count) AS all_total_count')
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}
