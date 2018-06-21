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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publisher;

    /**
     * @ORM\Column(type="integer", length=13, nullable=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="text", length=1500, nullable=true)
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleBooksId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainCategory;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $pageCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkSmallImageBook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $lastUpdateDate;



    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher)
    {
        $this->publisher = $publisher;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn)
    {
        $this->isbn = $isbn;
    }

    public function getSubject()
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
     * @return mixed
     */
    public function getGoogleBooksId()
    {
        return $this->googleBooksId;
    }

    /**
     * @param mixed $googleBooksId
     */
    public function setGoogleBooksId($googleBooksId)
    {
        $this->googleBooksId = $googleBooksId;
    }

    /**
     * @return mixed
     */
    public function getMainCategory()
    {
        return $this->mainCategory;
    }

    /**
     * @param mixed $mainCategory
     */
    public function setMainCategory($mainCategory)
    {
        $this->mainCategory = $mainCategory;
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @param mixed $pageCount
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }

    /**
     * @return mixed
     */
    public function getLinkSmallImageBook()
    {
        return $this->linkSmallImageBook;
    }

    /**
     * @param mixed $linkSmallImageBook
     */
    public function setLinkSmallImageBook($linkSmallImageBook)
    {
        $this->linkSmallImageBook = $linkSmallImageBook;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * @param \DateTime $lastUpdateDate
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
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
