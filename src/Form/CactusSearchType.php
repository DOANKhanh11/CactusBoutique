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
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Rechercher par nom…'],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Catégorie',
                'placeholder' => 'Toutes',
            ])
            ->add('niveauSoin', ChoiceType::class, [
                'required' => false,
                'label' => 'Niveau de soin',
                'placeholder' => 'Tous',
                'choices' => [
                    'Facile' => 'Facile',
                    'Moyen' => 'Moyen',
                    'Difficile' => 'Difficile',
                ],
            ])
            ->add('prixMin', NumberType::class, [
                'required' => false,
                'label' => 'Prix min (€)',
                'attr' => ['placeholder' => '0'],
            ])
            ->add('prixMax', NumberType::class, [
                'required' => false,
                'label' => 'Prix max (€)',
                'attr' => ['placeholder' => '999'],
            ])
            ->add('expirationAvant', DateType::class, [
                'required' => false,
                'label' => 'Expire avant le',
                'widget' => 'single_text',
            ])
            ->add('tri', ChoiceType::class, [
                'required' => false,
                'label' => 'Trier par',
                'placeholder' => 'Pertinence',
                'choices' => [
                    'Prix croissant' => 'prix_asc',
                    'Prix décroissant' => 'prix_desc',
                    'Plus récent' => 'date_desc',
                    'Expiration proche' => 'expiration_asc',
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
