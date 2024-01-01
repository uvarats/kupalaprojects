<?php

declare(strict_types=1);

namespace App\Enum;

enum NameFormatEnum: string
{
    /**
     * LastName FirstName (MiddleName)
     * @example Ivanov Ivan Ivanovich
     */
    case LAST_FIRST_MIDDLE = 'lfm';

    /**
     * FirstName MiddleName LastName
     * @example Ivan Ivanovich Ivanov
     */
    case FIRST_MIDDLE_LAST = 'fml';

    /**
     * FirstName MiddleName
     * @example Ivan Ivanovich
     */
    case FIRST_MIDDLE = 'fm';

    /**
     * FirstName LastName
     * @example Ivan Ivanov
     */
    case FIRST_LAST = 'fl';

    /**
     * LastName FirstName
     * @example Ivanov Ivan
     */
    case LAST_FIRST = 'lf';
}
