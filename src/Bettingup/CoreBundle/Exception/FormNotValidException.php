<?php

namespace Bettingup\CoreBundle\Exception;

use InvalidArgumentException;

use Symfony\Component\Form\FormError,
    Symfony\Component\Form\FormInterface;

/**
 * Thrown when a form is not valid.
 *
 * This exception should be used with an ApiException, but as a composition.
 */
class FormNotValidException extends InvalidArgumentException
{
    /** @var FormInterface */
    private $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getErrors()
    {
        return $this->getErrorsAsArray($this->form);
    }

    private function getErrorsAsArray(FormInterface $form)
    {
        $errors = ['errors'   => array_map(function (FormError $error) { return $error->getMessage(); }, $form->getErrors()),
                   'children' => []];

        foreach ($form as $key => $child) {
            if ($childErrors = $this->getErrorsAsArray($child)) {
                $errors['children'][$child->getName()] = $this->getErrorsAsArray($child);
            }
        }

        if (0 === count($errors['errors'])) {
            unset($errors['errors']);
        }

        if (0 === count($errors['children'])) {
            unset($errors['children']);
        }

        if (!isset($errors['errors']) && isset($errors['children'])) {
            return $errors['children'];
        }

        if (isset($errors['errors']) && !isset($errors['children'])) {
            return $errors['errors'];
        }

        return json_encode($errors);
    }
}

