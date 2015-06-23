<?php

namespace Acme\PortalBundle\Utility\Validator;


interface ValidatorInterface {
  /**
   * @return boolean
   */
  public function validate();
}