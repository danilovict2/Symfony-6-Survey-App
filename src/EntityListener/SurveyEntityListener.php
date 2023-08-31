<?php

namespace App\EntityListener;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::preRemove, entity: Survey::class)]
class SurveyEntityListener
{
    public function __construct(#[Autowire('%photo_dir%')] private string $photoDir)
    {
    }

    public function preRemove(Survey $survey, LifecycleEventArgs $args)
    {
        //unlink($this->photoDir . '/' . $survey->getImage()); 
    }
}
