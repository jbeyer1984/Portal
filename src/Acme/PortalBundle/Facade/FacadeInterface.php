<?php
namespace Acme\PortalBundle\Facade;

use Symfony\Bridge\Doctrine\ManagerRegistry;

interface FacadeInterface
{
  public function setFacade(ManagerRegistry $doctrine);
}