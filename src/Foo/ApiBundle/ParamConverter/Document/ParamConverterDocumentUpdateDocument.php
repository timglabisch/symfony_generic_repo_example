<?php

namespace Foo\ApiBundle\ParamConverter\Document;

use Foo\ApiBundle\Document\ApiRequestDocumentCreateNewDocument;
use Foo\ApiBundle\Document\ApiRequestDocumentUpdateDocument;
use Foo\Document\Domain\Document\DocumentReference;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ParamConverterDocumentUpdateDocument implements ParamConverterInterface
{

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === ApiRequestDocumentUpdateDocument::class;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {

        if (!($json = @json_decode($request->getContent(), true))) {
            throw new HttpException(400);
        }

        if (!isset($json['title']) || !is_string($json['title'])) {
            throw new HttpException(400, 'Title is Required');
        }

        if (!isset($request->attributes->get('_route_params')['documentId'])) {
            throw new HttpException(400, 'DocumentId is Required');
        };

        $request->attributes->set($configuration->getName(), new ApiRequestDocumentUpdateDocument(
            new DocumentReference($request->attributes->get('_route_params')['documentId']),
            $json['title']
        ));
    }
}