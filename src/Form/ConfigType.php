<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ConfigType extends AbstractType
{
    protected function getConfig($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ],
        ];
    }
}
