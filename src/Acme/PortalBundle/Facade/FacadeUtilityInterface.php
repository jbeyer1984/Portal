<?php
namespace Acme\PortalBundle\Facade;

use Acme\PortalBundle\Facade\Facade;
use Symfony\Component\HttpFoundation\Session\Session;

interface FacadeUtilityInterface
{
  /**
   * @param Facade $facade
   * @return mixed
   */
  public function setFacade(Facade $facade);

  /**
   * @param Session $session
   * @return mixed
   */
  public function setSession(Session $session);
}
