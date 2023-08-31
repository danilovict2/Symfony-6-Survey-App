<?php

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @extends ServiceEntityRepository<Survey>
 *
 * @method Survey|null find($id, $lockMode = null, $lockVersion = null)
 * @method Survey|null findOneBy(array $criteria, array $orderBy = null)
 * @method Survey[]    findAll()
 * @method Survey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private SluggerInterface $slugger)
    {
        parent::__construct($registry, Survey::class);
    }

    public function fillSurveyWithData(Survey &$survey, array $data): void
    {
        $survey->setCreatedBy($data['created_by'])
            ->setImage($data['image'])
            ->setTitle($data['title'])
            ->setStatus($data['status'])
            ->setDescription($data['description'])
            ->setExpireDate($data['expire_date'])
            ->computeSlug($this->slugger)
        ;
    }
}
