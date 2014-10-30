<?php
// src/Acme/PortalBundle/Form/Type/TagType.php
namespace Acme\PortalBundle\Form\Type;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', 'text', array(
//        'constraints' => new UniqueEntity()
      )
    );
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Acme\PortalBundle\Entity\Tag',
    ));
  }

  public function getDefaultOptions(array $options)
  {
    return array(
      'fields' => array('full', 'Default')
    );
  }

  public function getName()
  {
    return 'tag';
  }
}
