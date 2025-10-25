<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthorSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('min', IntegerType::class, [
                'required' => false,
                'label' => 'Nombre de livres min',
                'attr' => ['placeholder' => 'ex : 1']
            ])
            ->add('max', IntegerType::class, [
                'required' => false,
                'label' => 'Nombre de livres max',
                'attr' => ['placeholder' => 'ex : 10']
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher'
            ]);
    }
}
