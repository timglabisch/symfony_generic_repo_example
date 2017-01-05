<?php

namespace Foo\DocumentMysqlDoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Foo\Document\Domain\Document\DocumentInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="documents")
 */
class MysqlDoctrineDocument implements DocumentInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $created;

    public function getDocumentId(): string
    {
        return $this->getId();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

}