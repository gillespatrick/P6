<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends ConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfig('Trick Name', 'Enter Trick Name'))
            ->add('description', TextareaType::class, $this->getConfig('Description', 'Enter the trick description'))
            //->add('create_date')
            ->add('cover', FileType::class, ['data_class' => null], $this->getConfig('Url Image', 'Select your main image'))
            ->add('slug', TextType::class, $this->getconfig('Slug', 'This field is automatically filled', ['required' => false]))
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])

            // ->add('user')

            ->add('media', CollectionType::class, [
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ], );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
