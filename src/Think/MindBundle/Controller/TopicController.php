<?php

namespace Think\MindBundle\Controller;


use Think\MindBundle\Entity\Topic;
use Think\MindBundle\Form\Type\TopicType;
use Think\MindBundle\Helper\Depot\DepotControllerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Think\MindBundle\Helper\Depot\RepositoryDepot;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class TopicController extends Controller implements DepotControllerInterface
{

  /**
   * @var RepositoryDepot
   */
  protected $repository;

  public function setDepot(ManagerRegistry $doctrine)
  {
    $this->repository = new RepositoryDepot($doctrine, 'ThinkMindBundle');
//    $session = new Session();
//    $session->start();
  }
  
  public function showAction()
  {
    $topics = $this->repository->getEntity('Topic')->findAll();

    $topic = $this->getNewTopic();
    $form = $this->createForm(new TopicType(), $topic);

    $forms = array();
    foreach ($topics as $topic) {
      $forms[] = $this->createForm(new TopicType, $topic)->createView();
    }
    
    return $this->render('ThinkMindBundle:Mind/Topic:show.html.twig', array(
      'forms' => $forms
    ));
  }
  
  protected function getNewTopic()
  {
    $topic = New Topic();
//    $topic->setPos(0);
//    $topic->setDescription($name);
    return $topic;
  }
}