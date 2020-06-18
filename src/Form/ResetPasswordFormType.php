<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordFormType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('oldPassword', PasswordType::class, $this->getConfig('Old Password', ' Your Old Password...'))
            ->add('password', PasswordType::class, $this->getConfig('New Password', ' Your New Password...'))
            ->add('passwordConfirm', PasswordType::class, $this->getConfig('Confirm Password', 'Confirm Your Password...'))

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
