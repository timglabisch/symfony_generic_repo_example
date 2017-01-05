<?php

namespace Foo\Document\Domain\Document\Service;


use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\Commands\DocumentCommandCreateNewDocument;
use Foo\Document\Domain\Document\Commands\DocumentCommandUpdateDocument;
use Foo\Document\Domain\Document\DocumentInterface;
use Foo\Document\Domain\Document\DocumentReferenceInterface;

interface DocumentWriterInterface
{
    public function deleteDocument(DocumentReferenceInterface $documentReference, Context $context);

    public function updateDocument(DocumentCommandUpdateDocument $documentUpdateCommand, Context $context): DocumentInterface;

    public function createDocument(DocumentCommandCreateNewDocument $documentCreateCommand, Context $context): DocumentInterface;
}