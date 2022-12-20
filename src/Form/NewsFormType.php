<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', 
            TextType::class,
            [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'form4Example2',
                    'placeholder' => 'Enter title ...'
                ],
                'row_attr' =>[
                    'class' =>'col-md-6'
                ],
            ]
            )
            ->add(
                'description', 
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'form4Example2',
                        'placeholder' => 'Enter description ...'
                    ],
                    'row_attr' =>[
                        'class' =>'col-md-6'
                    ],
                ]
            )
            ->add(
                'image_url',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'form4Example2',
                        'placeholder' => 'Enter image url ...'
                    ],
                    'row_attr' =>[
                        'class' =>'col-md-6'
                    ],
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
