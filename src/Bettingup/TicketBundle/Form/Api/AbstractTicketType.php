<?php

namespace Bettingup\TicketBundle\Form\Api;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'text', ['mapped' => false]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Bettingup\\TicketBundle\\Entity\\AbstractTicket',
            'csrf_protection'   => false,
        ));
    }
}
