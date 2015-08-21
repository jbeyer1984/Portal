<?php
namespace Think\MindBundle\Helper\Depot;

use Symfony\Bridge\Doctrine\ManagerRegistry;

interface DepotControllerInterface
{
  public function setDepot(ManagerRegistry $doctrine);
}