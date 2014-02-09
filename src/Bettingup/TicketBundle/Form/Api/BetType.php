<?php

namespace Bettingup\TicketBundle\Form\Api;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('odds', 'number');
        $builder->add('home', 'integer');
        $builder->add('visitor', 'integer');
        $builder->add('competition', 'integer');
        $builder->add('bet_type', 'integer');
        $builder->add('pronostic', 'integer');
        $builder->add('status', 'checkbox');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Bettingup\\TicketBundle\\Entity\\Bet',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bet';
    }
}
