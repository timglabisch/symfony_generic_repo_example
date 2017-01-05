<?php

namespace Foo\ApiBundle\Document;


use Foo\Document\Domain\Document\Commands\DocumentCommandUpdateDocument;
use Foo\Document\Domain\Document\DocumentReferenceInterface;

class ApiRequestDocumentUpdateDocument implements DocumentCommandUpdateDocument
{
    /** @var DocumentReferenceInterface */
    private $documentReference;

    /** @var string */
    private $title;

    /**
     * ApiRequestDocumentCreateNewDocument constructor.
     * @param DocumentReferenceInterface $documentReference
     * @param string $title
     */
    public function __construct(
        DocumentReferenceInterface $documentReference,
        string $title
    ) {
        $this->documentReference = $documentReference;
        $this->title = $title;
    }

    public function getDocumentReference(): DocumentReferenceInterface
    {
        return $this->documentReference;
    }

    public function getDocumentTitle(): string
    {
        return $this->title;
    }


}