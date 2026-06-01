<?php

namespace App\Form;

use App\Entity\Terreau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerreauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'terreau.name'])
            ->add('composition', null, ['label' => 'terreau.composition'])
            ->add('prix', null, ['label' => 'terreau.price'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terreau::class,
        ]);
    }
}
