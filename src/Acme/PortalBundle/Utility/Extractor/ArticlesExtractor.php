<?php


namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Utility\Extractor\Extractor;
use Acme\PortalBundle\Utility\Extractor\ExtractorInterface;
use Acme\PortalBundle\Utility\Validator\ExtractorValidator;
use Acme\PortalBundle\Utility\Validator\ValidatorCollection;
use Acme\PortalBundle\Entity\Client;

class ArticlesExtractor extends Extractor {
  /**
   * @var Array $articles
   */
  protected $articles;

  /**
   * @return boolean
   */
  protected function validate()
  {
    $this->addExtractorValidation($this->extractor);
    return parent::validate();
  }

  /**
   * @param array $extractor
   */
  protected function addExtractorValidation(array $extractor)
  {
    $extractorValidator = new ExtractorValidator($extractor);
    $this->validatorCollection->add($extractorValidator);
  }

  /*
   * @param Client[]
   */
  protected function extractByClients(array $extractor)
  {
    /* @var $extractor Client[] */
    foreach ($extractor as $client) {
      foreach ($client->getArticles() as $article) {
        if (!in_array($article, $extractor)) {
          $this->articles[] = $article;
        }
      }
    }
  }

  /**
   * @return Array
   */
  public function getArticles()
  {
    return $this->articles;
  }

  /**
   * @param Array $articles
   */
  public function setArticles($articles)
  {
    $this->articles = $articles;
  }
  
}