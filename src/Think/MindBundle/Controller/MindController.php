<?php

namespace Think\MindBundle\Controller;

use Think\MindBundle\Helper\Depot\DepotControllerInterface;
use Think\MindBundle\Helper\Depot\RepositoryDepot;
use Think\MindBundle\Entity\Topic;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class MindController extends Controller implements DepotControllerInterface
{
  /**
   * @var RepositoryDepot
   */
  protected $repository;
  
  public function setDepot(ManagerRegistry $doctrine)
  {
    $this->repository = new RepositoryDepot($doctrine, 'ThinkMindBundle');
    $session = new Session();
    $session->start();
  }
  
  protected function createTopic($name)
  {
    $topic = New Topic();
    $topic->setPos(0);
    $topic->setDescription($name);
    
    $this->repository->getEm()->persist($topic);
    $this->repository->getEm()->flush();
  }
  
  public function createAction($name)
  {
    $this->createTopic($name);
    return $this->showAction();
  }
  
  public function showAction()
  {
    $topics = $this->repository->getEntity("Topic")->findAll();
    return $this->render('ThinkMindBundle:Mind:show.html.twig',
      array(
        'topics' => $topics,
      )
    );  
  }
}