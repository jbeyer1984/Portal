<?php


namespace Acme\PortalBundle\Utility\Validator;

use Acme\PortalBundle\Utility\Extractor\ExtractorInterface;
use Acme\PortalBundle\Utility\Validator\ValidatorInterface;


class ExtractorValidator implements ValidatorInterface {
  /**
   * @var Array
   */
  protected $extractor;

  function __construct(array $extractor)
  {
    $this->extractor = $extractor;
  }

  /**
   * @return boolean
   */
  public function validate()
  {
    if (empty($this->extractor)) {
      return false;
    }
    return true;
  }
}