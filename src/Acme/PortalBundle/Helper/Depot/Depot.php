<?php
namespace Acme\PortalBundle\Helper\Depot;

use Acme\PortalBundle\Helper\Depot\DepotInterface;

class Depot implements DepotInterface
{
  protected $facades;
  
  public function __construct(DepotInterface $depot){
    if (!$this->facades) {
      $this->facades = array();
    }
    $reflection = new \ReflectionClass($depot);
    $this->facades['get' . $reflection->getShortName()] = $depot;
  }

  /**
   * @return RepositoryDepot
   */
  public function getRepositoryDepot()
  {
    return $this->facades[__FUNCTION__];
  }
};