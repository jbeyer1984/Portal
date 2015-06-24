<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Validator\ValidatorCollection;

abstract class Extractor implements ExtractorInterface {
  /**
   * @var ValidatorCollection $validatorCollection
   */
  protected $validatorCollection;

  protected $extractor;

  function __construct()
  {
    $this->validatorCollection = new ValidatorCollection();
  }

  /**
   * @param array $extractor
   * @return $this
   */
  public function extractBy(array $extractor)
  {
    $this->extractor = $extractor;
    if (!$this->validate()) {
      //@TODO log nothing to extract
      return $this;
    }
    $this->callRelatedExtractorMethod($extractor);
    return $this;
  }

  /**
   * @return boolean
   */
  protected function validate()
  {
    return $this->validatorCollection->validateAll();
  }

  protected function callRelatedExtractorMethod($extractor)
  {
    $reflection = new \ReflectionClass($extractor[0]);
    $func = 'extractBy'.$reflection->getShortName().'s';
    call_user_func_array(array($this, $func), array($extractor));
//    $this->$func($extractor);
  }

  /**
   * @return ValidatorCollection
   */
  public function getValidatorCollection()
  {
    return $this->validatorCollection;
  }

  /**
   * @param ValidatorCollection $validatorCollection
   */
  public function setValidatorCollection($validatorCollection)
  {
    $this->validatorCollection = $validatorCollection;
  }
}