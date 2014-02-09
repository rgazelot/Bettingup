<?php

namespace Bettingup\TicketBundle\Form\Api;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profit', 'integer');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Bettingup\\TicketBundle\\Entity\\AbstractTicket',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'ticket';
    }
}
