<?php

namespace Foo\ApiBundle\ParamConverter;

use Foo\Document\Domain\ContextPermission\Context;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class ParamConverterContext implements ParamConverterInterface
{

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Context::class;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $request->attributes->set($configuration->getName(), new Context());
    }
}