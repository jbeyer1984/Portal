<?php

namespace Acme\PortalBundle\Utility\Extractor\Filter;

interface FilterInterface {
  /**
   * @param $toFilter
   * @return boolean
   */
  public function pass($toFilter);
}