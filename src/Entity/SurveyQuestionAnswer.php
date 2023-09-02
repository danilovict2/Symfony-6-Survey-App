<?php

namespace App\Entity;

use App\Repository\SurveyQuestionAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SurveyQuestionAnswerRepository::class)]
class SurveyQuestionAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SurveyQuestion $question = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SurveyAnswer $answer = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $fullAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?SurveyQuestion
    {
        return $this->question;
    }

    public function setQuestion(?SurveyQuestion $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?SurveyAnswer
    {
        return $this->answer;
    }

    public function setAnswer(?SurveyAnswer $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getFullAnswer(): ?string
    {
        return $this->fullAnswer;
    }

    public function setFullAnswer(string $fullAnswer): static
    {
        $this->fullAnswer = $fullAnswer;

        return $this;
    }
}
