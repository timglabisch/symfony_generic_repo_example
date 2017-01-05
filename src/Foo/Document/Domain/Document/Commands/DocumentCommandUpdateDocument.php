<?php

namespace Foo\Document\Domain\Document\Commands;


use Foo\Document\Domain\Document\DocumentReferenceInterface;

interface DocumentCommandUpdateDocument
{
    public function getDocumentReference(): DocumentReferenceInterface;

    public function getDocumentTitle();
}