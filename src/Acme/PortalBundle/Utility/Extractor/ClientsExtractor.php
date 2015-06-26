<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;
use Exception;

class ClientsExtractor extends Extractor
{

  /**
   * @param Client[] $toExtract
   * @param FilterInterface $filter
   * @return \String[]
   * @throws Exception
   */
  public function extract($toExtract, FilterInterface $filter = null)
  {
    $filter = $this->validateFilter($filter);
    $clientNames = array();
    foreach ($toExtract as $article) {
      /** @var $article Article */
      if ($filter->pass($article)) {
        $clientNames[] = $article->getClient()->getName();
      }
    }
    return $clientNames;
  }


}