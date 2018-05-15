<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $publisher;

    /**
     * @ORM\Column(type="integer", length=13)
     * @Assert\NotBlank()
     */
    private $isbn;

    /**
     * @ORM\Column(type="text", length=1500)
     */
    private $subject;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isInTheLibrary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $proposedBy;

    private $googleBooksId;

    /**
     * @ORM\Column(type="integer", length=10)
     * @Assert\NotBlank()
     */
    private $pageCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     * @Assert\DateTime
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime")
     * @Assert\DateTime
     */
    protected $lastUpdateDate;



    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher)
    {
        $this->publisher = $publisher;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn)
    {
        $this->isbn = $isbn;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getisInTheLibrary()
    {
        return $this->isInTheLibrary;
    }

    /**
     * @param mixed $isInTheLibrary
     */
    public function setIsInTheLibrary($isInTheLibrary)
    {
        $this->isInTheLibrary = $isInTheLibrary;
    }

    /**
     * @return mixed
     */
    public function getProposedBy()
    {
        return $this->proposedBy;
    }

    /**
     * @param mixed $proposedBy
     */
    public function setProposedBy($proposedBy)
    {
        $this->proposedBy = $proposedBy;
    }

    /**
     * Updates the correct date (s) as appropriate
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateTimestamps()
    {
        $this->setLastUpdateDate(new \DateTime());

        if ($this->getCreationDate() == null) {
            $this->setCreationDate(new \DateTime());
        }
    }

}
