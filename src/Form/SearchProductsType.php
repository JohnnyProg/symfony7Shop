<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')

            ->add('sortDirection', ChoiceType::class, [
                'choices' => [
                    'ascending' => true,
                    'descending' => false,
                ]
            ])
            ->add('sortBy', ChoiceType::class, [
                'choices' => [
                    'price' => 'price',
                    'date' => 'date',
                ]
            ])
            ->add('search', TextType::class,[
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Search',
            ])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
