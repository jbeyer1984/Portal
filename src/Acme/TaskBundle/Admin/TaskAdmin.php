<?php
// src/Acme/TaskBundle/Admin/TaskAdmin.php

namespace Acme\TaskBundle\Admin;

use Acme\TaskBundle\Form\DataTransformer\TagTransformer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TaskAdmin extends Admin
{
  // Fields to be shown on create/edit forms
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
//      ->add('description', 'entity', array('class' => 'Acme\TaskBundle\Entity\Task'))
      ->add('description', 'text')
      ->add(
        $formMapper->create('tags', 'choice', [
//            'data' => array('r', 'c'),
            'multiple' => true,
//          'delimiter' => ' ; ',
            'choices' => array('responsive'=>'responsive', 'cms'=>'cms', 'b'=>'blue')
          ])->addModelTransformer(new TagTransformer())
      )
//      ->add('tags', 'sonata_type_collection', array(),
//          array(
//            'edit' => 'inline',
//            'inline' => 'table',
//            'sortable' => 'position',
//          )
//        )
    ;
  }

  // Fields to be shown on filter forms
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('description')
    ;
  }

  // Fields to be shown on lists
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('description')
//      ->add('enabled', null, array('editable' => true))
    ;
  }
}