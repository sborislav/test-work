<?php

namespace sborislav\ApiBundle\Form;

use sborislav\ApiBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class modifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
            ->add('id', IntegerType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'constraints' => [
                        new Type("integer"),
                        new NotBlank(),
                    ]
                )
            )
            */
            ->add('email', EmailType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'constraints' => [
                        new Email(),
                        new Length(['min' => 3, 'max' => 50 ]),
                    ]
                )
            )
            ->add('lastName', TextType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'constraints' => [
                        new Length(['min' => 2, 'max' => 50 ]),
                        new Regex(['pattern' => "/\d/", 'match' => false,])
                    ]
                )
            )
            ->add('firstName', TextType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'constraints' => [
                        new Length(['min' => 2, 'max' => 50 ]),
                        new Regex(['pattern' => "/\d/", 'match' => false,])
                    ]

                )
            )
            ->add('state', ChoiceType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'placeholder' => 'Choose an option',
                    'choices'  => array(
                        'Online' => true,
                        'Offline' => false,
                    ),
                    'constraints' => [
                        new Type("bool"),
                    ]
                )
            )
            ->add('group', EntityType::class, array(
                    'label_attr' => array('class' => 'col-sm-2 control-label'),
                    'attr' =>array('class'=> 'form-control'),
                    'class' => 'ApiBundle:Group',
                    'choice_label' => 'name',
                    'placeholder' => 'Choose an option',

                )
            )
            ->add('save', ButtonType::class, array(
                    'label' => 'Modify user',
                    'attr' => array('class'=> 'btn btn-default')
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}