<?php

declare(strict_types=1);

namespace App\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Uvarats\Dto\Data;

final readonly class DTOResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType === null || !is_subclass_of($argumentType, Data::class)) {
            return [];
        }

        $data = $request->toArray();

        if ($data === []) {
            return [];
        }

        if (count($data) === 1 && isset($data['data'])) {
            $data = $data['data'];
        }

        return [$argumentType::from($data)];
    }

}
