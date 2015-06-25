<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Validator\ValidatorCollection;
use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;

abstract class Extractor implements ExtractorInterface {

  /**
   * @param $toExtract
   * @param FilterInterface $filter
   * @return mixed
   */
  abstract public function extract($toExtract, FilterInterface $filter);
}