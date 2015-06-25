<?php

namespace Acme\PortalBundle\Tests\Helper;

class EntityCreator
{
  /**
   * @param $class
   * @param array $properties
   * @param array $defaultProperties
   * @return mixed
   */
  public static function createObject($class, array $properties, array $defaultProperties)
  {
    $properties = array_merge($defaultProperties, $properties);

    $object = new $class();
    foreach ($properties as $property => $value)
    {
      $setter = 'set'.ucfirst($property);
      $object->$setter($value);
    }

    return $object;
  }

  /**
   * Creates a collection containing the passed objects.
   * @return Doctrine_Collection
   * @throws LogicException
   * @internal param Doctrine_Record $object1
   * @internal param $Doctrine_Record ...
   */
  public static function collection()
  {
    if (func_num_args() == 0)
    {
      throw new LogicException('You must pass at least one object');
    }

    $objects = func_get_args();
    $collection = new \Doctrine\Common\Collections\ArrayCollection($objects);
    foreach ($objects as $object)
    {
      $collection->add($object);
    }

    return $collection;
  }
}