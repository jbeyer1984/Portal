<?php
namespace Acme\PortalBundle\Helper\Depot;

use Acme\PortalBundle\Helper\Depot\Depot;
use Symfony\Component\HttpFoundation\Session\Session;

interface DepotUtilityInterface
{
  /**
   * @param Depot $depot
   * @return mixed
   */
  public function setDepot(Depot $depot);

  /**
   * @param Session $session
   * @return mixed
   */
  public function setSession(Session $session);
}
