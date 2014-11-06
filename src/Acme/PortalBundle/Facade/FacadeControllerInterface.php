<?php
namespace Acme\PortalBundle\Facade;

use Symfony\Bridge\Doctrine\ManagerRegistry;

interface FacadeControllerInterface
{
  public function setFacade(ManagerRegistry $doctrine);
}