<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CactusSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => 'search.name',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'search.category',
                'placeholder' => 'search.all_categories',
            ])
            ->add('niveauSoin', ChoiceType::class, [
                'required' => false,
                'label' => 'search.care_level',
                'placeholder' => 'search.all_levels',
                'choices' => [
                    'cactus.easy' => 'Facile',
                    'cactus.medium' => 'Moyen',
                    'cactus.hard' => 'Difficile',
                ],
            ])
            ->add('prixMin', NumberType::class, [
                'required' => false,
                'label' => 'search.price_min',
                'attr' => ['placeholder' => '0'],
            ])
            ->add('prixMax', NumberType::class, [
                'required' => false,
                'label' => 'search.price_max',
                'attr' => ['placeholder' => '999'],
            ])
            ->add('expirationAvant', DateType::class, [
                'required' => false,
                'label' => 'search.expiry_before',
                'widget' => 'single_text',
            ])
            ->add('tri', ChoiceType::class, [
                'required' => false,
                'label' => 'search.sort',
                'placeholder' => 'search.sort_default',
                'choices' => [
                    'search.sort_price_asc' => 'prix_asc',
                    'search.sort_price_desc' => 'prix_desc',
                    'search.sort_newest' => 'date_desc',
                    'search.sort_expiry' => 'expiration_asc',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
