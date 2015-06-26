<?php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Helper\Depot\DepotControllerInterface;
use Acme\PortalBundle\Helper\Depot\RepositoryDepot;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Acme\PortalBundle\Utility\PortalData;

class PortalController extends Controller implements DepotControllerInterface
{
  /**
   * @var RepositoryDepot
   */
  protected $repository;
  /**
   * @var PortalData
   */
  protected $portalData;

  public function setDepot(ManagerRegistry $doctrine)
  {
    $this->repository = new RepositoryDepot($doctrine, 'AcmePortalBundle');
    $this->portalData = new PortalData();
    $session = new Session();
    $session->start();
    $this->portalData->setDepot($this->repository);
    $this->portalData->setSession($session);
  }

  public function showAction()
  {
    $clients = $this->repository->getEntity('Client')->findAllOrderedByDescription();

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

  public function overviewAction()
  {
    return $this->render('AcmePortalBundle:Portal:overview.html.twig');
  }

  public function visitAction($client, $article)
  {
    $this->portalData->visit($client, $article);
    $articlesLeft = $this->portalData->getArticlesSorted();
    $clients = $this->repository->getEntity('Client')->findAllOrderedByDescription();

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

  public function deleteSessionAction()
  {
    $session = new Session();
    $session->clear();
    
    return $this->redirect(
      $this->generateUrl(
        'acme_portal_overview'
      )
    );
  }
}