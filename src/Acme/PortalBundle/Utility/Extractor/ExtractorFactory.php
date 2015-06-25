<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Filter\FilterInterface;
use Acme\PortalBundle\Utility\Extractor\Filter\ArticleFilter;
use Acme\PortalBundle\Utility\Extractor\Filter\ClientFilter;
use Acme\PortalBundle\Utility\Extractor\ArticleExtractor;
use Acme\PortalBundle\Utility\Extractor\ClientsExtractor;

class ExtractorFactory {
  private static $instance;

  static public function init()
  {
    if (!self::$instance) {
      self::$instance = new ExtractorFactory();
    }
    return self::$instance;
  }
  
  public function extractArticleEntitiesFromClient($toExtract)
  {
    $clientsExtractor = new ClientsExtractor();
    $articles = $clientsExtractor->extract($toExtract, new ArticleFilter());
    return $articles;
  }
  
  public function extractClientNamesFromArticles($toExtract)
  {
    $articleExtractor = new ArticlesExtractor();
    $clients = $articleExtractor->extract($toExtract, new ClientFilter());
    return $clients;
  }
}