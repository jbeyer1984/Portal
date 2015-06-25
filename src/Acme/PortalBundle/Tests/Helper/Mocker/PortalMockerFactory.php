<?php

namespace Acme\PortalBundle\Tests\Helper\Mocker;

use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerEntities;
use Acme\PortalBundle\Tests\Helper\Mocker\PortalMockerData;

class PortalMockerFactory {
  private static $instance;
  /**
   * @var $portalMocker PortalMockerEntities
   */
  protected static $portalMockerEntities;
  /**
   * @var $portalMockerData PortalMockerData
   */
  protected static $portalMockerData;
  
  public static function init() {
    if (!self::$instance) {
      self::$instance = new PortalMockerFactory();
      self::$portalMockerEntities = new PortalMockerEntities();
      self::$portalMockerData = new PortalMockerData();
    }
    return self::$instance;
  }
  
  public function getClientData()
  {
      return self::$portalMockerData->getStructuredClients();
  }
  
  public function getPortalMockerEntities()
  {
    return self::$portalMockerEntities;
  }
}