<?php

namespace Foo\Document\Domain\Document;


interface DocumentInterface extends DocumentReferenceInterface
{
    public function getTitle(): string;

    public function getCreated(): \DateTime;
}