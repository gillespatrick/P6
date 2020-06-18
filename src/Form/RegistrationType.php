<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, $this->getConfig('Username', 'Enter your username'))
            ->add('email', EmailType::class, $this->getConfig('Email', 'Enter your email '))
            ->add('password', PasswordType::class, $this->getConfig('Password', 'Add an password'))
            ->add('passwordConfirm', PasswordType::class, $this->getConfig('Confirm password', 'Confirm your password'))
            //->add('img', UrlType::class, $this->getConfig('Avatar', 'Add your avatar !'))
            ->add('img', FileType::class, ['data_class' => null], $this->getConfig('Your Avatar', 'Avatar'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
