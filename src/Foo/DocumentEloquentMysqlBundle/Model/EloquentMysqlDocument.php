<?php

namespace Foo\DocumentEloquentMysqlBundle\Model;


use Foo\Document\Domain\Document\DocumentInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentMysqlDocument extends Model implements DocumentInterface
{
    protected $table = 'documents';

    public $timestamps = false;

    public $incrementing = false;

    public function getDocumentId(): string
    {
        return $this->attributes['id'];
    }

    public function getId(): string
    {
        return $this->attributes['id'];
    }

    public function setId(string $id)
    {
        $this->attributes['id'] = $id;
    }

    public function getTitle(): string
    {
        return $this->attributes['title'];
    }

    public function setTitle(string $title)
    {
        $this->attributes['title'] = $title;
    }

    public function getCreated(): \DateTime
    {
        // well, Eloquent makes it a bit hard to name a column created if the property timestamp is false
        // for tonight i am just skipping this...
        return new \DateTime();
    }

    public function setCreated(\DateTime $created)
    {

    }
}