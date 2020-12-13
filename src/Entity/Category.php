<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
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
    private $title;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="Category")
     */
    private $articles;


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
    /**
     * toString
     * @return string
     */
    public function __toString() {
        return $this->getTitle();
    }
}
