<?php

namespace Acme\PortalBundle\Utility\Extractor\Filter;

abstract class Filter implements FilterInterface
{
  /**
   * @param $toFilter
   * @return mixed
   */
  abstract public function pass($toFilter);
}