<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir le champ 'titre'")
     * @Assert\Length(max=200,
     *     maxMessage="Le champ doit contenir moins de 200 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capital;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="articles")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Filter::class, inversedBy="articles")
     */
    private $filter_continent;

    /**
     * @ORM\ManyToOne(targetEntity=Filter::class)
     */
    private $filter_climat;

    /**
     * @ORM\ManyToOne(targetEntity=Filter::class)
     */
    private $filter_activities;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="article")
     */
    private $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFilterContinent(): ?Filter
    {
        return $this->filter_continent;
    }

    public function setFilterContinent(?Filter $filter_continent): self
    {
        $this->filter_continent = $filter_continent;

        return $this;
    }

    public function getFilterClimat(): ?Filter
    {
        return $this->filter_climat;
    }

    public function setFilterClimat(?Filter $filter_climat): self
    {
        $this->filter_climat = $filter_climat;

        return $this;
    }

    public function getFilterActivities(): ?Filter
    {
        return $this->filter_activities;
    }

    public function setFilterActivities(?Filter $filter_activities): self
    {
        $this->filter_activities = $filter_activities;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setArticle($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getArticle() === $this) {
                $medium->setArticle(null);
            }
        }

        return $this;
    }
}
