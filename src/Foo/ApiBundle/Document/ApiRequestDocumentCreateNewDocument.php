<?php

namespace Foo\ApiBundle\Document;


use Foo\Document\Domain\Document\Commands\DocumentCommandCreateNewDocument;

class ApiRequestDocumentCreateNewDocument implements DocumentCommandCreateNewDocument
{
    private $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getDocumentTitle()
    {
        return $this->title;
    }

}