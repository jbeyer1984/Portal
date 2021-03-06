<?php

// src/Acme/PortalBundle/Form/Type/ArticleType.php
namespace Acme\PortalBundle\Form\Type;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;

class ArticleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('id', 'hidden');
    $builder->add('description');
    $builder->add('pos');

    $builder->add('tags', 'entity', array(
        'class' => 'AcmePortalBundle:Tag',
        'property' => 'name',
        'expanded' => 'true',
        'multiple' => 'true',
//        'choices' => $group->getUsers()
      )
    );
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Acme\PortalBundle\Entity\Article',
    ));
  }

  public function getName()
  {
    return 'article';
  }
}
