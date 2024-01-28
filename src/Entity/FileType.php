<?php

namespace App\Entity;

enum FileType: string
{
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
}