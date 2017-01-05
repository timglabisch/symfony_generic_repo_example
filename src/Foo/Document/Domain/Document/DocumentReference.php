<?php

namespace Foo\Document\Domain\Document;


class DocumentReference implements DocumentReferenceInterface
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getDocumentId(): string
    {
        return $this->id;
    }

}