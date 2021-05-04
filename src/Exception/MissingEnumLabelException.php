<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\EnumInterface;
use Exception;

class MissingEnumLabelException extends Exception
{
    public function __construct(EnumInterface $enum)
    {
        $message = sprintf('Missing label for enum %s::%s', get_class($enum), $enum->getKey());

        parent::__construct($message);
    }
}