<?php
// src/Acme/TaskBundle/Admin/TagAdmin.php

namespace Acme\TaskBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TagAdmin extends Admin
{
  protected $parentAssociationMapping = 'task';
  
  // Fields to be shown on create/edit forms
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
//      ->add('name', 'entity', array('class' => 'Acme\TaskBundle\Entity\Tag'))
        
      ->add('name', 'text')
//      ->add('tag')
//      ->add('tag', 'sonata_type_collection', array(),
//        array(
//          'edit' => 'inline',
//          'inline' => 'table',
//          'sortable' => 'position',
//        )
//        )
      ->add(
        $formMapper->create('name', 'choice', [
//            'data' => array('r', 'c'),
            'multiple' => true,
//          'delimiter' => ' ; ',
            'choices' => array('responsive'=>'responsive', 'cms'=>'cms', 'b'=>'blue')            
          ])->addModelTransformer(new TagTransformer())
      )
    ;
  }

  // Fields to be shown on filter forms
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name')
    ;
  }

  // Fields to be shown on lists
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name')
    ;
  }
}
