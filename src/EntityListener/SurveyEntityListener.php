<?php 

namespace App\EntityListener;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Survey::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Survey::class)]
class SurveyEntityListener
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function prePersist(Survey $survey, LifecycleEventArgs $args)
    {
        $survey->computeSlug($this->slugger);
    }

    public function preUpdate(Survey $survey, LifecycleEventArgs $args)
    {
        $survey->computeSlug($this->slugger);
    }
}