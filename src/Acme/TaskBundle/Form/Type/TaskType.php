<?php

// src/Acme/TaskBundle/Form/Type/TaskType.php
namespace Acme\TaskBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('id', 'hidden');
    $builder->add('description');
    $builder->add('order');

    $builder->add('tags', 'collection', array(
      'type'         => new TagType(),
      'allow_add'    => true,
      'allow_delete' => true,
      'by_reference' => false,
    ));
    $builder->add('save', 'submit', array('label' => 'Create Post'));
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Acme\TaskBundle\Entity\Task',
    ));
  }

  public function getName()
  {
    return 'task';
  }
}
