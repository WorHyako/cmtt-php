<?php

namespace App\Repository;

use App\Entity\Ad;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     *
     * <pre>
     *
     * </pre>
     *
     * @param Ad $ad
     *
     * @deprecated Don't use it. In progress...
     *
     * @return Ad|null
     */
    public function findOneMoreRelevant(Ad $ad): ?Ad
    {
        return $this->createQueryBuilder('a');
    }

    /**
     *
     *
     * <pre>
     *
     * </pre>
     *
     * @param Ad $ad
     *
     * @return Ad[] Returns an array of Ad objects
     */
    public function findByFields(Ad $ad): array
    {
        return $this->createQueryBuilder('query')
            ->andWhere('query.text = :text')
            ->andWhere('query.price = :price')
            ->andWhere('query.banner = :banner')
            ->andWhere('query.showLimit = :showLimit')
            ->setParameter('text', $ad->getText())
            ->setParameter('price', $ad->getPrice())
            ->setParameter('banner', $ad->getBanner())
            ->setParameter('showLimit', $ad->getShowLimit())
            ->orderBy('query.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     *
     *
     * <pre>
     *
     * </pre>
     *
     * @param Ad $ad
     *
     * @return Ad|null
     */
    public function findOneByFields(Ad $ad): ?Ad
    {
        return $this->createQueryBuilder('query')
            ->andWhere('query.text = :text')
            ->andWhere('query.price = :price')
            ->andWhere('query.banner = :banner')
            ->andWhere('query.showLimit = :showLimit')
            ->setParameter('text', $ad->getText())
            ->setParameter('price', $ad->getPrice())
            ->setParameter('banner', $ad->getBanner())
            ->setParameter('showLimit', $ad->getShowLimit())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
