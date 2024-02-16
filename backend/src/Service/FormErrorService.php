<?php
namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\FormErrorIterator;

class FormErrorService
{

    /**
     * @param FormInterface $form
     * @return arraty
     */

    public static function generateErrorsArrayFromForm(FormInterface $form) :array
    {
        $result = [];
        foreach ($form->getErrors(true, false) as $formError) {
            if ($formError instanceof FormError) {
                $result[$formError->getOrigin()->getName()] = $formError->getMessage();
            } elseif ($formError instanceof FormErrorIterator) {
                $result[$formError->getForm()->getName()] = self::generateErrorsArrayFromForm($formError->getForm());
            }
        }

        return $result;
    }
}