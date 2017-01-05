<?php

namespace Foo\DocumentRoundRobinBundle\Service;


use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\Commands\DocumentCommandCreateNewDocument;
use Foo\Document\Domain\Document\Commands\DocumentCommandUpdateDocument;
use Foo\Document\Domain\Document\DocumentInterface;
use Foo\Document\Domain\Document\DocumentReferenceInterface;
use Foo\Document\Domain\Document\Service\DocumentReaderInterface;
use Foo\Document\Domain\Document\Service\DocumentWriterInterface;

class DocumentRoundRobinService implements DocumentReaderInterface, DocumentWriterInterface
{
    /** @var DocumentReaderInterface[] | DocumentWriterInterface[] */
    private $drivers = [];

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    /** @return DocumentReaderInterface | DocumentWriterInterface */
    private function getRandomDriver()
    {
        return $this->drivers[array_rand($this->drivers, 1)];
    }

    public function loadDocument(DocumentReferenceInterface $documentReference, Context $context)
    {
        return $this->getRandomDriver()->loadDocument($documentReference, $context);
    }

    public function loadDocuments(Context $context)
    {
        return $this->getRandomDriver()->loadDocuments($context);
    }

    public function deleteDocument(DocumentReferenceInterface $documentReference, Context $context)
    {
        return $this->getRandomDriver()->deleteDocument($documentReference, $context);
    }

    public function updateDocument(DocumentCommandUpdateDocument $documentUpdateCommand, Context $context): DocumentInterface
    {
        return $this->getRandomDriver()->updateDocument($documentUpdateCommand, $context);
    }

    public function createDocument(DocumentCommandCreateNewDocument $documentCreateCommand, Context $context): DocumentInterface
    {
        return $this->getRandomDriver()->createDocument($documentCreateCommand, $context);
    }


}