<?php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Article;
use Acme\PortalBundle\Helper\Depot\DepotControllerInterface;
use Acme\PortalBundle\Helper\Depot\RepositoryDepot;
use Acme\PortalBundle\Form\Type\ClientType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller implements DepotControllerInterface
{
  /**
   * @var RepositoryDepot
   */
  protected $repository;

  public function setDepot(ManagerRegistry $doctrine)
  {
    $this->repository = new RepositoryDepot($doctrine, 'AcmePortalBundle');
  }

  public function indexAction()
  {
    
  }

  public function newAction(Request $request)
  {
    $client = $this->getNewClient();

    $form = $this->createForm(new ClientType(), $client);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $client = $form->getData();
      $this->repository->getEm()->persist($client);
      $this->repository->getEm()->flush();
      foreach($client->getArticles() as $article) {
        $client->addArticle($article);
        $this->repository->getEm()->persist($article);
      }
      $this->repository->getEm()->persist($client);
      $this->repository->getEm()->flush();
    }

    $clients = $this->repository->getEntity('Client')->findAllOrderedByDescription();

    $form = $form->createView();

    return $this->render('AcmePortalBundle:Client:new.html.twig',
      array(
        'clients' => $clients,
        'form' => $form
      )
    );
  }

  public function showAction()
  {
    $clients = $this->repository->getEntity('Client')->findAllOrderedByDescription();

    $client = $this->getNewClient();
    $form = $this->createForm(new ClientType(), $client);

    $forms = array();
    foreach ($clients as $client) {
      $forms[] = $this->createForm(new ClientType, $client)->createView();
    }
//    $forms[] = $form->createView();

    return $this->render('AcmePortalBundle:Client:show.html.twig',
      array(
        'clients' => $clients,
        'forms' => $forms
      )
    );
  }


  public function editAction(Request $request)
  {
    $client = $this->getNewClient();
    $form = $this->createForm(new ClientType(), $client);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $clientReq = $form->getData();
      $clientDb = $this->repository->getEntity('Client')->findById($clientReq->getId());
      if (sizeof($clientDb) > 0) {
        $client = $clientDb[0];
        $client->setName($clientReq->getName());
        // remove first the tags from array
        foreach($client->getArticles() as $article) {
          $article->removeClient($client);
          $this->repository->getEm()->persist($article);
        }
        $this->repository->getEm()->persist($client);
        $articlesReq = $clientReq->getArticles();
        // add article to array
        foreach($articlesReq as $article) {
          $client->addArticle($article);
          $this->repository->getEm()->persist($article);
        }
        $this->repository->getEm()->persist($client);
        $this->repository->getEm()->flush();
      }
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_clients_show'
      )
    );
  }

  public function deleteAction($id)
  {
    $clientDb = $this->repository->getEntity('Client')->findById($id);
    if (sizeof($clientDb) > 0) {
      $client = $clientDb[0];
      foreach($client->getArticles() as $article) {
        $article->removeClient($client);
        $this->repository->getEm()->persist($article);
      }
      $this->repository->getEm()->remove($client);
      $this->repository->getEm()->flush();
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_clients_show'
      )
    );
  }
  
  
  protected function getNewClient()
  {
    $client = new Client();
    $client->setPos(0);

    return $client;
  }
}