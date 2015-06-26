<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Filter\ArticleFilter;
use Acme\PortalBundle\Utility\Extractor\Filter\ClientFilter;
use Acme\PortalBundle\Entity\Article;

class ExtractorProxy {
  private static $instance;
  /**
   * @var $extractorFactory ExtractorFactory
   */
  private static $extractorFactory;

  static public function init()
  {
    if (!self::$instance) {
      self::$instance = new ExtractorProxy();
      self::$extractorFactory = ExtractorFactory::init();
    }
    return self::$instance;
  }

  /**
   * @param $toExtract
   * @return Article[]
   */
  public function extractArticleEntitiesFromClient($toExtract)
  {
    $clientsExtractor = self::$extractorFactory->getClientsExtractor();
    $articles = $clientsExtractor->extract($toExtract, new ArticleFilter());
    return $articles;
  }

  /**
   * @param $toExtract
   * @return String[]
   */
  public function extractClientNamesFromArticles($toExtract)
  {
    $articleExtractor = self::$extractorFactory->getArticlesExtractor();
    $clients = $articleExtractor->extract($toExtract, new ClientFilter());
    return $clients;
  }
}