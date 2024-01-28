<?php

namespace App\Utils;

use Symfony\Component\Form\FormInterface;

class FormHelper
{
    public static function getErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form as $child) {
            $childErrors = self::getErrors($child);
            if (empty($childErrors)) {
                continue;
            }

            $errors[$child->getName()] = $childErrors;
        }

        return $errors;
    }
}