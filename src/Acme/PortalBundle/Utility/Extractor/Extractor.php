<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Validator\ValidatorCollection;

abstract class Extractor {
  /**
   * @var ValidatorCollection $validatorCollection
   */
  protected $validatorCollection;

  function __construct()
  {
    $this->validatorCollection = new ValidatorCollection();
  }

  /**
   * @param array $extractor
   * @return boolean
   */
  protected function validate()
  {
    return $this->validatorCollection->validateAll();
  }

  /**
   * @param array $extractor
   * @return $this
   */
  public function extractBy(array $extractor)
  {
    if (!$this->validate($extractor)) {
      //@TODO log nothing to extract
      return $this;
    }
    $this->callRelatedExtractorMethod($extractor);
    return $this;
  }

  public function callRelatedExtractorMethod($extractor)
  {
    $reflection = new \ReflectionClass($extractor[0]);
    $func = 'extractBy'.$reflection->getShortName().'s';
    $this->$func($extractor);
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