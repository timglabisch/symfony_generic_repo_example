<?php

namespace Foo\Document\Domain\Document;


interface DocumentReferenceInterface
{
    public function getDocumentId(): string;
}