<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;

interface ExtractorInterface
{
  /**
   * @param $toExtract
   * @param FilterInterface $filter
   * @return mixed
   */
  public function extract($toExtract, FilterInterface $filter = null);
}