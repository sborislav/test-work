<?php

namespace sborislav\ApiBundle\Form;

use sborislav\ApiBundle\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;


class newGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array( 'label_attr' => array('class' => 'col-sm-2 control-label'), 'attr' =>array('class'=> 'form-control') ) )
            ->add('save', ButtonType::class, array('label' => 'Create group', 'attr' => array('class'=> 'btn btn-default')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Group::class,
        ));
    }
}