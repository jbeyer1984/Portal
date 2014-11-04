<?php

// src/Acme/PortalBundle/Form/Type/ArticleType.php
namespace Acme\PortalBundle\Form\Type;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;

class ClientType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('id', 'hidden');
    $builder->add('client_id', 'hidden');
    $builder->add('name');
    $builder->add('pos');

    $builder->add('articles', 'entity', array(
        'class' => 'AcmePortalBundle:Article',
        'property' => 'description',
        'expanded' => 'true',
        'multiple' => 'true',
//        'choices' => $group->getUsers()
      )
    );
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Acme\PortalBundle\Entity\Client',
    ));
  }

  public function getName()
  {
    return 'client';
  }
}
