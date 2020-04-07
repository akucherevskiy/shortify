<?php

namespace App\Repository;

use App\Entity\UrlStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class UrlStatRepository extends ServiceEntityRepository
{
    /**
     * @var int
     */
    private $time;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlStat::class);
        $this->time = time();
    }

    /**
     * @param int $id
     * @return int
     * @throws NonUniqueResultException
     */
    public function getDailyAccess(int $id): int
    {
        return $this->getCount($id, $this->time - 60 * 60 * 24, true);
    }

    /**
     * @param int $id
     * @return int
     * @throws NonUniqueResultException
     */
    public function getWeeklyAccess(int $id): int
    {
        return $this->getCount($id, $this->time - 60 * 60 * 24 * 7, true);
    }

    /**
     * @param int $id
     * @return int
     * @throws NonUniqueResultException
     */
    public function getAllTimeAccess(int $id): int
    {
        return $this->getCount($id);
    }

    /**
     * @param $id
     * @param $ago
     * @param bool $allTime
     * @return int
     * @throws NonUniqueResultException
     */
    private function getCount($id, $ago = null, $allTime = false): int
    {
        $builder = $this->createQueryBuilder('stat')
            ->select('count(stat.id)')
            ->where('stat.url = :id')
            ->setParameter('id', $id);
        if ($allTime) {
            $builder
                ->andWhere('stat.accessedAt < :now AND stat.accessedAt > :ago')
                ->setParameter('now', $this->time)
                ->setParameter('ago', $ago);
        }
        $count = $builder
            ->getQuery()
            ->getOneOrNullResult();

        return $count[1] ?? 0;
    }
}
