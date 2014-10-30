<?php
namespace Acme\PortalBundle\Facade;

use Symfony\Bridge\Doctrine\ManagerRegistry;

class RepositoryFacade
{
  protected $doctrine;
  protected $em;
  protected $bundle;
  protected $repositories;

  /**
   * @param ManagerRegistry $doctrine
   * @param $bundle String
   */
  public function __construct(ManagerRegistry $doctrine, $bundle)
  {
    $this->doctrine = $doctrine;
    $this->bundle = $bundle;
  }

  public function getEm()
  {
    if (!$this->em) {
      $this->em = $this->doctrine->getManager(); 
    }
    return $this->em;
  }
  
  public function getRepository($identifier)
  {
    if (isset($this->repositories[$identifier])) {
      return $this->repositories[$identifier];
    }
    $repository = $this->getEm()->getRepository($this->bundle . ':' . $identifier);
    $this->repositories[$identifier] = $repository;
    return $repository;
  }
}