<?php

namespace App\Entity;

enum TokenType: string
{
    case AUTHENTICATION = 'AUTHENTICATION';
    case FORGOT_PASSWORD = 'FORGOT_PASSWORD';
    case RESET_PASSWORD = 'RESET_PASSWORD';
}