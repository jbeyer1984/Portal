<?php

namespace Acme\PortalBundle\EventListener;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Acme\PortalBundle\Facade\FacadeInterface;

class BeforeControllerListener
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  public function onKernelController(FilterControllerEvent $event)
  {
    $controller = $event->getController();

    if (!is_array($controller)) {
      // not a object but a different kind of callable. Do nothing
      return;
    }

    $controllerObject = $controller[0];

    // skip initializing for exceptions
    if ($controllerObject instanceof ExceptionController) {
      return;
    }

    if ($controllerObject instanceof FacadeInterface) {
      // this method is the one that is part of the interface.
      $controllerObject->setFacade($this->doctrine);
    }
  }
}
