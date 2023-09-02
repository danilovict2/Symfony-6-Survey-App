<?php

namespace App\Entity;

use App\Repository\SurveyQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SurveyQuestionRepository::class)]
class SurveyQuestion
{
    public const QUESTION_TYPES = ["text", "select", "radio", "checkbox", "textarea"];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank]
    #[Assert\Choice(self::QUESTION_TYPES)]
    #[Assert\Length(max: 45)]
    private ?string $type = null;

    #[ORM\Column(length: 2000)]
    #[Assert\NotBlank(message: 'Please provide all questions')]
    #[Assert\Length(max: 2000, maxMessage: 'Title cannot be longer than {{ limit }} characters')]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    #[ORM\ManyToOne(inversedBy: 'surveyQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Survey $survey = null;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->type,
            'question' => $this->question,
            'description' => $this->description,
            'data' => json_decode($this->data)
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): static
    {
        $this->survey = $survey;

        return $this;
    }

}
