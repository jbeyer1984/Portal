<?php
namespace Acme\PortalBundle\Helper\Depot;

use Symfony\Bridge\Doctrine\ManagerRegistry;

interface DepotControllerInterface
{
  public function setDepot(ManagerRegistry $doctrine);
}