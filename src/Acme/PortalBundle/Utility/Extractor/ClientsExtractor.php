<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;

class ClientsExtractor extends Extractor {

  /**
   * @param Client[] $toExtract
   * @param FilterInterface $filter
   * @return mixed
   */
  public function extract($toExtract, FilterInterface $filter) {
    return array_map(function($article) {
      /** @var $article Article */
//      if ($filter->pass($article)) {
        return $article->getClient()->getName();
//      }
    }, $toExtract);
//    return array_map(function($client, $filter) {
//      if ($filter->pass($client)) {
//        return $client->getClient();
//      }
//    }, $toExtract);
  }
}