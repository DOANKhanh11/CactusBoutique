<?php

namespace App\Form;

use App\Entity\Cactus;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CactusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'cactus.name'])
            ->add('description', null, ['label' => 'cactus.description'])
            ->add('prix', null, ['label' => 'cactus.price'])
            ->add('niveauSoin', null, ['label' => 'cactus.care'])
            ->add('arrosage', null, ['label' => 'cactus.watering'])
            ->add('taille', null, ['label' => 'cactus.size'])
            ->add('dateExpiration', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'cactus.expiry',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'cactus.category',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cactus::class,
        ]);
    }
}
