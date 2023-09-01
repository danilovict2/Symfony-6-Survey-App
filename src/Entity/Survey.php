<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SurveyRepository::class)]
#[UniqueEntity('title', message: 'Survey with this title already exists!')]
#[UniqueEntity('slug', message: 'Survey with this title already exists!')]
class Survey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'surveys')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Image is required!')]
    private ?string $image = null;

    #[ORM\Column(length: 1000, unique: true)]
    #[Assert\NotBlank(message: 'Title is required!')]
    #[Assert\Length(max: 1000, maxMessage: 'Title cannot be longer than {{ limit }} characters')]
    private ?string $title = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expireDate = null;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyQuestion::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $surveyQuestions;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyAnswer::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $surveyAnswers;

    #[ORM\Column(length: 1000, unique: true)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->surveyQuestions = new ArrayCollection();
        $this->surveyAnswers = new ArrayCollection();
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        $this->slug = (string) $slugger->slug($this->title)->lower();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'description' => $this->description,
            'expire_date' => $this->expireDate?->format('Y-m-d'),
            'questions' => array_map(fn($question) => $question->toArray(), $this->surveyQuestions->toArray())
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

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

    public function getExpireDate(): ?\DateTimeImmutable
    {
        return $this->expireDate;
    }

    public function setExpireDate(?\DateTimeImmutable $expireDate): static
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * @return Collection<int, SurveyQuestion>
     */
    public function getSurveyQuestions(): Collection
    {
        return $this->surveyQuestions;
    }

    public function addSurveyQuestion(SurveyQuestion $surveyQuestion): static
    {
        if (!$this->surveyQuestions->contains($surveyQuestion)) {
            $this->surveyQuestions->add($surveyQuestion);
            $surveyQuestion->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyQuestion(SurveyQuestion $surveyQuestion): static
    {
        if ($this->surveyQuestions->removeElement($surveyQuestion)) {
            // set the owning side to null (unless already changed)
            if ($surveyQuestion->getSurvey() === $this) {
                $surveyQuestion->setSurvey(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SurveyAnswer>
     */
    public function getSurveyAnswers(): Collection
    {
        return $this->surveyAnswers;
    }

    public function addSurveyAnswer(SurveyAnswer $surveyAnswer): static
    {
        if (!$this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers->add($surveyAnswer);
            $surveyAnswer->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyAnswer(SurveyAnswer $surveyAnswer): static
    {
        if ($this->surveyAnswers->removeElement($surveyAnswer)) {
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getSurvey() === $this) {
                $surveyAnswer->setSurvey(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
