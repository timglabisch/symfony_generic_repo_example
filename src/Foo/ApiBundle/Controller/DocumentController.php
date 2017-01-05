<?php

namespace Foo\ApiBundle\Controller;

use Foo\ApiBundle\Document\ApiRequestDocumentCreateNewDocument;
use Foo\ApiBundle\Document\ApiRequestDocumentUpdateDocument;
use Foo\Document\Domain\ContextPermission\Context;
use Foo\Document\Domain\Document\DocumentInterface;
use Foo\Document\Domain\Document\DocumentReference;
use Foo\Document\Domain\Document\DocumentReferenceInterface;
use Foo\Document\Domain\Document\Service\DocumentReaderInterface;
use Foo\Document\Domain\Document\Service\DocumentWriterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(service="api_controller_document")
 */
class DocumentController
{
    /** @var DocumentReaderInterface */
    private $documentReader;

    /** @var DocumentWriterInterface */
    private $documentWriter;

    public function __construct(
        DocumentReaderInterface $documentReader,
        DocumentWriterInterface $documentWriter
    ) {
        $this->documentReader = $documentReader;
        $this->documentWriter = $documentWriter;
    }

    /** @Route(name="api_document_get", path="/{documentId}", methods={"GET"}) */
    public function getAction($documentId, Context $context)
    {
        return new JsonResponse([
            'document' => $this->transformDocument(
                $this->tryLoadDocument(
                    new DocumentReference($documentId),
                    $context
                )
            )
        ]);
    }

    /** @Route(name="api_document_list", path="/", methods={"GET"}) */
    public function listAction(Context $context)
    {
        $documents = $this->documentReader->loadDocuments($context);

        return new JsonResponse([
            'documents' => array_map(function(DocumentInterface $document) {
                return $this->transformDocument($document);
            }, $documents)
        ]);
    }

    /** @Route(name="api_document_delete", path="/{documentId}", methods={"DELETE"}) */
    public function deleteAction($documentId, Context $context)
    {
        $this->documentWriter->deleteDocument(
            $this->tryLoadDocument(new DocumentReference($documentId), $context),
            $context
        );

        return new Response();
    }

    /** @Route(name="api_document_put", path="/{documentId}", methods={"PUT"}) */
    public function putAction(ApiRequestDocumentUpdateDocument $updateDocumentApiRequest, Context $context)
    {
        $this->tryLoadDocument($updateDocumentApiRequest->getDocumentReference(), $context);

        return new JsonResponse([
            'document' => $this->transformDocument(
                $this->documentWriter->updateDocument(
                    $updateDocumentApiRequest,
                    $context
                )
            )
        ]);
    }

    /** @Route(name="api_document_create", path="/", methods={"POST"}) */
    public function postAction(ApiRequestDocumentCreateNewDocument $ceateNewDocumentApiRequest, Context $context)
    {
        return new JsonResponse([
            'document' => $this->transformDocument(
                $this->documentWriter->createDocument(
                    $ceateNewDocumentApiRequest,
                    $context
                )
            )
        ]);
    }

    private function transformDocument(DocumentInterface $document)
    {
        return [
            'id' => $document->getDocumentId(),
            'title' => $document->getTitle(),
            'created' => $document->getCreated()->format('d.m.Y'),
            'type' => get_class($document),
        ];
    }

    private function tryLoadDocument(DocumentReferenceInterface $documentReference, Context $context)
    {
        if (!($document = $this->documentReader->loadDocument($documentReference, $context))) {
            throw new NotFoundHttpException();
        }

        return $document;
    }
}
