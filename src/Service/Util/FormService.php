<?php

declare(strict_types=1);

namespace App\Service\Util;

use App\FormConfig\FormFieldsConfig;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * ????
 * TODO research
 */
class FormService
{
    public function disableFilledFields(
        object $entity,
        FormFieldsConfig $fieldsConfiguration
    ): FormFieldsConfig {
        $accessor = PropertyAccess::createPropertyAccessor();

        $configurations = $fieldsConfiguration->getFieldConfig();
        foreach ($configurations as $field => $config) {
            $value = $accessor->getValue($entity, $field);

            if ($value === null) {
                continue;
            }

            $config->setOption('disabled', true);
        }

        return $fieldsConfiguration;
    }
}
