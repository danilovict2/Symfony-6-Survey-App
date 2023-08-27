<?php

namespace App\Entity;

use App\Repository\SurveyQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SurveyQuestionRepository::class)]
class SurveyQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $type = null;

    #[ORM\Column(length: 2000)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;

    #[ORM\ManyToOne(inversedBy: 'surveyQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Survey $survey = null;

    #[ORM\ManyToMany(targetEntity: SurveyAnswer::class)]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    /**
     * @return Collection<int, SurveyAnswer>
     */
    public function getanswers(): Collection
    {
        return $this->answers;
    }

    public function addReanswer(SurveyAnswer $reanswer): static
    {
        if (!$this->answers->contains($reanswer)) {
            $this->answers->add($reanswer);
        }

        return $this;
    }

    public function removeReanswer(SurveyAnswer $reanswer): static
    {
        $this->answers->removeElement($reanswer);

        return $this;
    }
}
