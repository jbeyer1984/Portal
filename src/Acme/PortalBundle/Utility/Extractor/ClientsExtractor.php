<?php

namespace Acme\PortalBundle\Utility\Extractor;

use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Utility\Validator\ValidatorCollection;
use Acme\PortalBundle\Utility\Validator\ExtractorValidator;

class ClientsExtractor extends Extractor implements ExtractorInterface {
  /**
   * @var Array $clients
   */
  protected $clients;

  /**
   * @param array $extractor
   * @return boolean
   */
  protected function validate(array $extractor)
  {
    $this->addExtractorValidation($extractor);
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
   * @param Article[]
   */
  protected function extractByArticles(array $extractor)
  {
    /* @var $articles Article[] */
    $this->clients = array_map(function ($article) {
      return $article->getClient()->getName();
    }, $extractor);
  }

  /**
   * @return array
   */
  public function getClients()
  {
    return $this->clients;
  }

  /**
   * @param array $clients
   */
  public function setClients(array $clients)
  {
    $this->clients = $clients;
  }
  
}