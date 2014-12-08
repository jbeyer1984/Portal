<?php
namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Entity\Client;
use Acme\PortalBundle\Entity\Article;
//use Acme\PortalBundle\Entity\Tag;
//use Doctrine\Common\Collections\ArrayCollection;
use Acme\PortalBundle\Facade\FacadeControllerInterface;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Acme\PortalBundle\Form\Type\ClientType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller implements FacadeControllerInterface
{
  /**
   * @var RepositoryFacade
   */
  protected $facade;

  public function setFacade(ManagerRegistry $doctrine)
  {
    $this->facade = new RepositoryFacade($doctrine, 'AcmePortalBundle');
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
      $this->facade->getEm()->persist($client);
      $this->facade->getEm()->flush();
      foreach($client->getArticles() as $article) {
        $client->addArticle($article);
        $this->facade->getEm()->persist($article);
      }
      $this->facade->getEm()->persist($client);
      $this->facade->getEm()->flush();
    }

    $clients = $this->facade->getRepository('Client')->findAllOrderedByDescription();

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
    $clients = $this->facade->getRepository('Client')->findAllOrderedByDescription();

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
      $clientDb = $this->facade->getRepository('Client')->findById($clientReq->getId());
      if (sizeof($clientDb) > 0) {
        $client = $clientDb[0];
        $client->setName($clientReq->getName());
        // remove first the tags from array
        foreach($client->getArticles() as $article) {
          $article->removeClient($client);
          $this->facade->getEm()->persist($article);
        }
        $this->facade->getEm()->persist($client);
        $articlesReq = $clientReq->getArticles();
        // add article to array
        foreach($articlesReq as $article) {
          $client->addArticle($article);
          $this->facade->getEm()->persist($article);
        }
        $this->facade->getEm()->persist($client);
        $this->facade->getEm()->flush();
      }
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_client_show'
      )
    );
  }

  public function deleteAction($id)
  {
    $clientDb = $this->facade->getRepository('Client')->findById($id);
    if (sizeof($clientDb) > 0) {
      $client = $clientDb[0];
      foreach($client->getArticles() as $article) {
        $article->removeClient($client);
        $this->facade->getEm()->persist($article);
      }
      $this->facade->getEm()->remove($client);
      $this->facade->getEm()->flush();
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_client_show'
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