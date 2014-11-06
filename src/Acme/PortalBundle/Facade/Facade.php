<?php
namespace Acme\PortalBundle\Facade;

class Facade
{
  protected $facades;
  
  public function __construct(Facade $facade){
    if (!$this->facades) {
      $this->facades = array();
    }
    $this->facades['get' . $facade->toString()] = $facade;
  }

  /**
   * @return RepositoryFacade
   */
  public function getRepositoryFacade()
  {
    return $this->facades[__FUNCTION__];
  }
};