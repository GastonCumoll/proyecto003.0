<?php

namespace App\Form;

use App\Entity\Edicion;
use App\Entity\Publicacion;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EdicionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('fechaDeEdicion')
            ->add('cantidadImpresiones')
            //->add('fechaYHoraCreacion')
            ->add('publicacion')
            ->add('usuarioCreador')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Edicion::class,
        ]);
    }
}
