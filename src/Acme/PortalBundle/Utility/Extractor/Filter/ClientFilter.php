<?php

namespace Acme\PortalBundle\Utility\Extractor\Filter;

use Acme\PortalBundle\Entity\Client;
use Exception;

class ClientFilter extends Filter
{
  /**
   * @param Client $toFilter
   * @return bool
   * @throws Exception
   */
  public function pass($toFilter)
  {
    if ($toFilter instanceof Client) {
      return true;
    }
    throw new Exception($toFilter." not instance of Article");
  }
}