<?php

namespace App\Repository;

use App\Entity\Ad;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 *
 * @author WorHyako
 *
 * @since 0.0.1
 */
class AdRepository extends ServiceEntityRepository
{
    /**
     * Ctor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * Sorts ads with next weight: 10% - text length, 90% - price.
     *
     * <pre>
     *          usort($adsArray, array($this, "relevantAdsSort"));
     * </pre>
     *
     * @param Ad $first First ad
     *
     * @param Ad $second Second ad
     *
     * @return bool Comparing result
     */
    private function relevantAdsSort(Ad $first, Ad $second): bool
    {
        $priceWeight = [$first->getPrice() * 0.9, $second->getPrice() * 0.9];
        $textWeight = [strlen($first->getText()) * 0.1, strlen($second->getText()) * 0.1];

        return ($priceWeight[0] + $textWeight[0]) < ($priceWeight[1] + $textWeight[1]);
    }

    /**
     * Returns more relevant ads.
     *
     * <pre>
     *          $ad = $adRepository->findOneMoreRelevant();
     * </pre>
     *
     * @return Ad|null object
     *
     * @see AdRepository::relevantAdsSort()
     */
    public function findOneMoreRelevant(): ?Ad
    {
        /**
         * TODO: extract price, limit, text length and banner length to global space.
         */
        $priciestAds = $this->createQueryBuilder('query')
            ->andWhere('query.showCount < :showLimit')
            ->setParameter('showLimit', Ad::$showLimit)
            ->orderBy('query.price', 'DESC')
            ->getQuery()
            ->setMaxResults(3)
            ->getResult();

        if (count($priciestAds) === 0) {
            return null;
        }

        usort($priciestAds, array($this, "relevantAdsSort"));

        return $priciestAds[0];
    }

    /**
     * Returns all ads with fields like <code>$ad</code>.
     * <p/>
     * 'id' and 'showCount' fields don't account.
     *
     * <pre>
     *          $ad = $adRepository->findByFields((new Ad)
     *              ->setText("text")
     *              ->setPrice(price)
     *              ->setBanner("banner");
     * </pre>
     *
     * @param Ad $ad field container
     *
     * @return Ad[] array of Ad objects
     */
    public function findByFields(Ad $ad): array
    {
        return $this->createQueryBuilder('query')
            ->andWhere('query.text = :text')
            ->andWhere('query.price = :price')
            ->andWhere('query.banner = :banner')
            ->setParameter('text', $ad->getText())
            ->setParameter('price', $ad->getPrice())
            ->setParameter('banner', $ad->getBanner())
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns one ad with fields like <code>$ad</code>.
     *
     * <pre>
     *          $ad = $adRepository->findByFields((new Ad)
     *              ->setText("text")
     *              ->setPrice(price)
     *              ->setBanner("banner");
     * </pre>
     *
     * @param Ad $ad fields container
     *
     * @return Ad|null object
     */
    public function findOneByFields(Ad $ad): ?Ad
    {
        return $this->createQueryBuilder('query')
            ->andWhere('query.text = :text')
            ->andWhere('query.price = :price')
            ->andWhere('query.banner = :banner')
            ->setParameter('text', $ad->getText())
            ->setParameter('price', $ad->getPrice())
            ->setParameter('banner', $ad->getBanner())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
