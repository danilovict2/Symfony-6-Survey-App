<?php

namespace App\Repository;

use App\Entity\SurveyAnswer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SurveyAnswer>
 *
 * @method SurveyAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyAnswer[]    findAll()
 * @method SurveyAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyAnswerRepository extends ServiceEntityRepository
{
    private const LATEST_ANSWERS_LIMIT = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswer::class);
    }

    public function getUserAnswersCount(User $user): int
    {
        return $this->getAllUserAnswersQueryBuilder($user)->select('count(sa.id)')->getQuery()->getSingleScalarResult();
    }

    public function getLatestUserAnswers(User $user): array
    {
        return $this->getAllUserAnswersQueryBuilder($user)
            ->orderBy('sa.endDate', 'DESC')
            ->setMaxResults(self::LATEST_ANSWERS_LIMIT)
            ->getQuery()
            ->execute()
        ;
    }

    private function getAllUserAnswersQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('sa')
            ->join('sa.survey', 's')
            ->where('s.createdBy = :createdBy')
            ->setParameter('createdBy', $user)
        ;
    }
}
