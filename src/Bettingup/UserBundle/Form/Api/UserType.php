<?php

namespace Bettingup\UserBundle\Form\Api;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('email', 'text');
        $builder->add('password', 'text', ['mapped' => false]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Bettingup\\UserBundle\\Entity\\User',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'user';
    }
}
