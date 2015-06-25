<?php


namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Extractor;
use Acme\PortalBundle\Utility\Extractor\ExtractorInterface;
use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;
use Acme\PortalBundle\Entity\Client;

class ArticlesExtractor extends Extractor {

  /**
   * @param Client[] $toExtract
   * @param FilterInterface $filter
   * @return mixed
   */
  public function extract($toExtract, FilterInterface $filter) {
    $articles = array();
    foreach ($toExtract as $client) {
      if ($filter->pass($client)) {
        $articles = array_merge($articles, $client->getArticles()->toArray());
      }
    }
    return $articles;
  }
}