<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ConfigurationType extends AbstractType
{
    /**
     * @param string $label
     * @param string $placeholder
     * @param array  $options
     *
     * @return array
     */
    protected function getConfig($label, $placeholder, $options = [])
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ],
        ], $options);
    }
}
