<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Published = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $PublishDate = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;
    #[ORM\Column(length: 50, nullable: true)]
private ?string $category = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->Published;
    }

    public function setPublished(?bool $Published): static
    {
        $this->Published = $Published;

        return $this;
    }

    public function getPublishDate(): ?\DateTime
    {
        return $this->PublishDate;
    }

    public function setPublishDate(?\DateTime $PublishDate): static
    {
        $this->PublishDate = $PublishDate;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }
    public function getCategory(): ?string
{
    return $this->category;
}

public function setCategory(?string $category): static
{
    $this->category = $category;
    return $this;
}
   
}
