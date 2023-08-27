<?php

namespace App\Repository;

use App\Entity\SurveyAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswer::class);
    }
}
