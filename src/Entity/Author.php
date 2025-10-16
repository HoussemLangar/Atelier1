<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $nbBooks = 0;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class, cascade: ['persist', 'remove'])]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->nbBooks = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nbBooks;
    }

    public function setNbBooks(int $nbBooks): static
    {
        $this->nbBooks = $nbBooks;
        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
            $this->nbBooks++;
        }
        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
                $this->nbBooks = max(0, $this->nbBooks - 1);
            }
        }
        return $this;
    }
}
