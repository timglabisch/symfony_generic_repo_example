<?php

namespace Foo\Document\Domain\Document\Service;


use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\DocumentReferenceInterface;

interface DocumentReaderInterface
{
    public function loadDocument(DocumentReferenceInterface $documentReference, Context $context);

    public function loadDocuments(Context $context);
}