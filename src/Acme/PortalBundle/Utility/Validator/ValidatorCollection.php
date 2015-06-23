<?php


namespace Acme\PortalBundle\Utility\Validator;

use Acme\PortalBundle\Utility\Validator\ValidatorInterface;

class ValidatorCollection {
  /**
   * @var ValidatorInterface[]
   */
  protected $validators;

  function __construct()
  {
    $this->validators = array();
  }


  public function add(ValidatorInterface $validator)
  {
    $this->validators[get_class($validator)] = $validator;
  }
  
  public function sub(ValidatorInterface $validator)
  {
    unset($this->validators[get_class($validator)]);
  }

  public function validateAll()
  {
    foreach ($this->validators as $validator) {
      if (false == $validator->validate()) {
        return false;
      }
    }
    return true;
  }
}