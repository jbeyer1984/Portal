<?php

namespace Acme\PortalBundle\Controller;

use Acme\PortalBundle\Entity\Tag;
use Acme\PortalBundle\Facade\FacadeInterface;
use Acme\PortalBundle\Facade\RepositoryFacade;
use Acme\PortalBundle\Form\Type\TagType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagController extends Controller implements FacadeInterface
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
    return $this->render('AcmePortalBundle:Default:index.html.twig', array('name' => 'hugo'));
  }
  
  public function newAction(Request $request)
  {
    $tag = $this->getNewTag();
    $form = $this->createForm(new TagType(), $tag);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $tag = $form->getData();
      $this->facade->getEm()->persist($tag);
      $this->facade->getEm()->flush();
    }
    $tags = $this->facade->getRepository('Tag')->findAllOrderedByName();
    $tagList = array();
    $tagNames = array();
    foreach ($tags as $key => $tag) {
      $tagNames['name'] = $tag->getName(); 
      $tagNames['shortName'] = substr($tag->getName(), 0, 3);
      $tagList[] = $tagNames;
    }
    $listOfTags = $tags;

    return $this->render('AcmePortalBundle:Tag:new.html.twig',
      array(
        'tagList' => $tags,
        'form' => $form->createView()
      )
    );
  }
  
  public function showAction()
  {
    $tags = $this->facade->getRepository('Tag')->findAllOrderedByName();

    $forms = array();
    foreach ($tags as $key => $tag) {
      $forms[] = $this
        ->createForm(new TagType(), $tag)
        ->createView();
    }

    return $this->render(
      'AcmePortalBundle:Tag:show.html.twig',
      array(
        'tags' => $tags,
        'forms' => $forms
      )
    );
  }
  
  public function ajaxAction()
  {
    
  }

  public function editAction(Request $request)
  {
    $tag = $this->getNewTag();
    $form = $this->createForm(new TagType(), $tag);
    
    $tagReq = $form->handleRequest($request)->getData();
    $tagDb = $this->facade->getRepository('Tag')->findById($tagReq->getId());
    
    
    if ($form->isValid()) {
      if (sizeof($tagDb) > 0) {
        $tag = $tagDb[0];
        $tag->setName($tagReq->getName());
        $this->facade->getEm()->persist($tag);
        $this->facade->getEm()->flush();
      }
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_tag_show'
      )
    );
  }
  
  public function deleteAction($id) {
    $tagDb = $this->facade->getRepository('Tag')->findById($id);
    if (sizeof($tagDb) > 0) {
      $tag = $tagDb[0];
      $this->facade->getEm()->remove($tag);
      $this->facade->getEm()->flush();
    }

    return $this->redirect(
      $this->generateUrl(
        'acme_tag_show'
      )
    );
  }

  protected function getNewTag()
  {
    $tag = new Tag();
    $tag->setName('new');
  }
}