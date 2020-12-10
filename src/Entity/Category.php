<?php

namespace App\Entity;

use App\Repository\CategorysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $genre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     *@ORM\OneToMany(targetEntity=Article::class, mappedBy="Category")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="Category")
     */
    private $new_article;

    public function __construct()
    {
        $this->new_article = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getNewArticle(): Collection
    {
        return $this->new_article;
    }

    public function addNewArticle(Article $newArticle): self
    {
        if (!$this->new_article->contains($newArticle)) {
            $this->new_article[] = $newArticle;
            $newArticle->addCategory($this);
        }

        return $this;
    }

    public function removeNewArticle(Article $newArticle): self
    {
        if ($this->new_article->removeElement($newArticle)) {
            $newArticle->removeCategory($this);
        }

        return $this;
    }
}
