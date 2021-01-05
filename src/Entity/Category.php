<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="veuillez donner un titre")
     * @ORM\Column(type="string", length=255)
     */
    private $title;


    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @Assert\NotBlank(message="veuillez choisir une date")
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @Assert\NotBlank(message="veuillez remplir le champ")
     * @ORM\Column(type="string", length=6500)
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="Category")
     */
    private $article;

    /**
     *
     * @Assert\Image(
     *    maxWidth = 400,maxHeight = 400
     * )
     * @ORM\Column(type="string", length=6500)
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="Category")
     */
    private $photo;

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }


    public function getArticle()
    {
        return $this->article;
    }


    public function setArticle($article): void
    {
        $this->article = $article;
    }

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate( $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * toString
     * @return string
     */
    public function __toString() {
        return $this->getTitle();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
