<?php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Facade\FacadeControllerInterface;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Acme\PortalBundle\Utility\PortalData;

class PortalController extends Controller implements FacadeControllerInterface
{
  /**
   * @var RepositoryFacade
   */
  protected $facade;
  /**
   * @var PortalData
   */
  protected $portalData;

  public function setFacade(ManagerRegistry $doctrine)
  {
    $this->facade = new RepositoryFacade($doctrine, 'AcmePortalBundle');
    $this->portalData = new PortalData();
    $session = new Session();
    $session->start();
    $this->portalData->setFacade($this->facade);
    $this->portalData->setSession($session);
  }

  public function showAction()
  {
    $clients = $this->facade->getRepository('Client')->findAllOrderedByDescription();

    $session = new Session();
    $sessionArr = $session->get('overview');
//    ob_start();
//    print_r($sessionArr);
//    $print = ob_get_clean();
//    error_log('dump:$sessionArr = ' . $print, 0, '/tmp/error.log');      
    
    return $this->render('AcmePortalBundle:Portal:show.html.twig',
      array(
        'clients' => $clients,
      )
    );
  }

  public function visitAction($client, $article)
  {
    $this->portalData->visit($client, $article);
    $articlesLeft = $this->portalData->getArticles();
    $clients = $this->facade->getRepository('Client')->findAllOrderedByDescription();

    return $this->render('AcmePortalBundle:Portal:visit.html.twig',
      array(
        'articlesLeft' => $articlesLeft,
        'clients' => $clients
      )
    );
//    return $this->redirect(
//      $this->generateUrl(
//        'acme_portal_show'
//      )
//    );
  }
}