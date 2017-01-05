<?php

namespace Foo\DocumentMysqlDoctrineBundle\Service;


use Doctrine\ORM\EntityManager;
use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\Commands\DocumentCommandCreateNewDocument;
use Foo\Document\Domain\Document\Commands\DocumentCommandUpdateDocument;
use Foo\Document\Domain\Document\DocumentInterface;
use Foo\Document\Domain\Document\DocumentReferenceInterface;
use Foo\Document\Domain\Document\Service\DocumentReaderInterface;
use Foo\Document\Domain\Document\Service\DocumentWriterInterface;
use Foo\DocumentMysqlDoctrineBundle\Entity\MysqlDoctrineDocument;

class DocumentMysqlDoctrineService implements DocumentReaderInterface, DocumentWriterInterface
{

    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadDocument(DocumentReferenceInterface $documentReference, Context $context)
    {

        if ($documentReference instanceof MysqlDoctrineDocument) {
            return $documentReference;
        }

        return $this->em->getRepository(MysqlDoctrineDocument::class)->find($documentReference->getDocumentId());
    }

    public function deleteDocument(DocumentReferenceInterface $documentReference, Context $context) {
        $document = $this->loadDocument($documentReference, $context);
        $this->em->remove($document);
        $this->em->flush($document);
    }

    public function updateDocument(DocumentCommandUpdateDocument $documentUpdateCommand, Context $context): DocumentInterface
    {
        $document = $this->loadDocument($documentUpdateCommand->getDocumentReference(), $context);

        $document->setTitle($documentUpdateCommand->getDocumentTitle());

        $this->em->persist($document);
        $this->em->flush($document);

        return $document;
    }

    public function loadDocuments(Context $context)
    {
        return $this->em->getRepository(MysqlDoctrineDocument::class)->findAll();
    }

    public function createDocument(DocumentCommandCreateNewDocument $documentCreateCommand, Context $context): DocumentInterface
    {
        $document = new MysqlDoctrineDocument();
        $document->setTitle($documentCreateCommand->getDocumentTitle());
        $document->setCreated(new \DateTime());

        $this->em->persist($document);
        $this->em->flush($document);

        return $document;
    }


}