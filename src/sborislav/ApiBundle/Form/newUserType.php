<?php

namespace sborislav\ApiBundle\Form;

use sborislav\ApiBundle\Entity\Group;
use sborislav\ApiBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class newUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label_attr'  => array('class' => 'col-sm-2 control-label'),
                'attr'        =>array('class'=> 'form-control'),
                'constraints' => [
                    new Email(),
                    new Length(['min' => 3, 'max' => 50 ]),
                    new NotBlank(),
                    ]
            ) )
            ->add('lastName', TextType::class, array(
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'attr'       => array('class' => 'form-control'),
                'constraints' => [
                    new Length(['min' => 2, 'max' => 50 ]),
                    new NotBlank(),
                    new Regex(['pattern' => "/\d/", 'match' => false,])
                    ]
            ) )
            ->add('firstName', TextType::class, array(
                'label_attr' => array('class' => 'col-sm-2 control-label'),
                'attr'       => array('class'=> 'form-control'),
                'constraints' => [
                    new Length(['min' => 2, 'max' => 50 ]),
                    new NotBlank(),
                    new Regex(['pattern' => "/\d/", 'match' => false,])
                    ]
            ) )
            ->add('group', EntityType::class, array(
                'label_attr'   => array('class' => 'col-sm-2 control-label'),
                'attr'         => array('class'=> 'form-control'),
                'class'        => 'ApiBundle:Group',
                'choice_label' => 'name',
                'constraints' => [
                //    new NotBlank(),
                    ]
            ) )
            ->add('save', ButtonType::class, array(
                'label' => 'Create user',
                'attr' => array('class'=> 'btn btn-default')
            ) )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}