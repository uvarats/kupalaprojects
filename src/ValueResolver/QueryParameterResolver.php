<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class QueryParameterResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(MapQueryParameter::class);

        if ($attributes === []) {
            return [];
        }

        $parameterName = $argument->getName();
        $parameter = $request->query->get($parameterName);

        if ($parameter !== null) {
            $type = $argument->getType();
            settype($parameter, $type);
        }

        return [$parameter];
    }
}
