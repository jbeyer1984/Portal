<?php
namespace Think\MindBundle\Helper\Depot;

use Think\MindBundle\Helper\Depot\Depot;
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
