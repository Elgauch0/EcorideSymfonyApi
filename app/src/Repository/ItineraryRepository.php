<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\Itinerary;
use App\Model\CarpoolSearchDto;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Itinerary>
 */
class ItineraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Itinerary::class);
    }

    /**
     * @return Itinerary[] Returns an array of Itinerary objects
     */
    public function findBySearchCriteria(CarpoolSearchDto $searchDto): array
    {
        $qb = $this->createQueryBuilder('i');
        $depart = trim($searchDto->depart);
        $destination = trim($searchDto->destination);
        $searchDate = new DateTimeImmutable($searchDto->date);

        $qb->where('i.departureCity = :depart')
            ->andWhere('i.arrivalCity = :destination');

        $qb->andWhere('i.datetime >= :startOfDay')
            ->andWhere('i.datetime < :endOfDay');

        $qb->andWhere('i.isFinished = :isFinished')
            ->andWhere('i.places > :minPlaces');

        $qb->setParameter('depart', $depart)
            ->setParameter('destination', $destination)
            ->setParameter('startOfDay', $searchDate->format('Y-m-d 00:00:00'))
            ->setParameter('endOfDay', $searchDate->modify('+1 day')->format('Y-m-d 00:00:00'))
            ->setParameter('isFinished', false)
            ->setParameter('minPlaces', 0);

        $qb->orderBy('i.datetime', 'ASC');

        return $qb->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?Itinerary
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
