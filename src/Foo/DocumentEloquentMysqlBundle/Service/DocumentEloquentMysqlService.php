<?php

namespace Foo\DocumentEloquentMysqlBundle\Service;


use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\Commands\DocumentCommandCreateNewDocument;
use Foo\Document\Domain\Document\Commands\DocumentCommandUpdateDocument;
use Foo\Document\Domain\Document\DocumentInterface;
use Foo\Document\Domain\Document\DocumentReferenceInterface;
use Foo\Document\Domain\Document\Service\DocumentReaderInterface;
use Foo\Document\Domain\Document\Service\DocumentWriterInterface;
use Foo\DocumentEloquentMysqlBundle\Model\EloquentMysqlDocument;
use Illuminate\Database\Capsule\Manager as Capsule;

class DocumentEloquentMysqlService implements DocumentReaderInterface, DocumentWriterInterface
{

    public function __construct()
    {
        // well, ...
        // @see https://github.com/wouterj/WouterJEloquentBundle/issues/31
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'username' => 'root',
            'password' => null,
            'database' => 'symfony',
            'collation' => 'utf-8'
        ]);
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
    }

    /**
     * @param DocumentReferenceInterface $documentReference
     * @param Context $context
     * @return EloquentMysqlDocument
     */
    public function loadDocument(DocumentReferenceInterface $documentReference, Context $context) {

        if ($documentReference instanceof EloquentMysqlDocument) {
            return $documentReference;
        }

        return EloquentMysqlDocument::find($documentReference->getDocumentId());
    }

    public function loadDocuments(Context $context)
    {
        return iterator_to_array(EloquentMysqlDocument::all()->getIterator());
    }

    public function deleteDocument(DocumentReferenceInterface $documentReference, Context $context)
    {
        $document = $this->loadDocument($documentReference, $context);

        $document->delete();
    }

    public function updateDocument(DocumentCommandUpdateDocument $documentUpdateCommand, Context $context): DocumentInterface
    {
        $document = $this->loadDocument($documentUpdateCommand->getDocumentReference(), $context);
        $document->setTitle($documentUpdateCommand->getDocumentTitle());
        $document->save();
        return $document;
    }

    public function createDocument(DocumentCommandCreateNewDocument $documentCreateCommand, Context $context): DocumentInterface
    {
        $document = new EloquentMysqlDocument();
        $document->setId(uniqid()); // no_real_uuid_support :(
        $document->setTitle($documentCreateCommand->getDocumentTitle());
        $document->setCreated(new \DateTime());
        $document->save();

        return $document;
    }


}