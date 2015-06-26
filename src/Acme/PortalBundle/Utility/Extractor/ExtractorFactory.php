<?php

namespace Acme\PortalBundle\Utility\Extractor;

class ExtractorFactory {
  /**
   * @var $instance ExtractorFactory
   */
  private static $instance;

  /**
   * @return ExtractorFactory
   */
  static public function init()
  {
    if (!self::$instance) {
      self::$instance = new ExtractorFactory();
    }
    return self::$instance;
  }

  /**
   * @return ArticlesExtractor
   */
  public function getArticlesExtractor()
  {
    return new ArticlesExtractor();
  }

  /**
   * @return ClientsExtractor
   */
  public function getClientsExtractor()
  {
    return new ClientsExtractor();
  }
}