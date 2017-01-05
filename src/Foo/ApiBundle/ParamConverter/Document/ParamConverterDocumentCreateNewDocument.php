<?php

namespace Foo\ApiBundle\ParamConverter\Document;

use Foo\ApiBundle\Document\ApiRequestDocumentCreateNewDocument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ParamConverterDocumentCreateNewDocument implements ParamConverterInterface
{

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === ApiRequestDocumentCreateNewDocument::class;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {

        if (!($json = @json_decode($request->getContent(), true))) {
            throw new HttpException(400);
        }

        if (!isset($json['title']) || !is_string($json['title'])) {
            throw new HttpException(400, 'Title is Required');
        }

        $request->attributes->set($configuration->getName(), new ApiRequestDocumentCreateNewDocument(
            $json['title']
        ));
    }
}