<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfig('Old Password', ' Your Old Password...'))
            ->add('newPassword', PasswordType::class, $this->getConfig('New Password', ' Your New Password...'))
            ->add('confirmPassword', PasswordType::class, $this->getConfig('Confirm Password', 'Confirm Your Password...'))

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
