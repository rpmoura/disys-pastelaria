<?php

namespace App\Enums;

enum ErrorEnum: int
{
    case ACCESS_DENIED = 1;
    case VALIDATE_FORM = 2;
}
