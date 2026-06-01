<?php

namespace App\Form;

use App\Entity\Pot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'pot.name'])
            ->add('material', null, ['label' => 'pot.material'])
            ->add('couleur', null, ['label' => 'pot.couleur'])
            ->add('prix', null, ['label' => 'pot.price'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pot::class,
        ]);
    }
}
