<?php

// src/Acme/TaskBundle/Controller/TaskController.php
namespace Acme\TaskBundle\Controller;

use Acme\TaskBundle\Entity\Task;
use Acme\TaskBundle\Entity\Tag;
use Acme\TaskBundle\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{
  protected $repositories = array();
  
  protected $em;
  
  public function indexAction()
  {
    return $this->render('AcmeTaskBundle:Default:index.html.twig', array('name' => 'test'));
  }

  /**
   * @return \Doctrine\Common\Persistence\ObjectManager|object
   */
  protected function getEm()
  {
    if (isset($this->em)) {
      return $this->em;
    }
    $this->em = $this->getDoctrine()->getManager();
    return $this->em;
  }

  /**
   * @param $identifier
   * @return EntityRepository
   */
  protected function getRepository($identifier)
  {
    
    if (isset($this->repositories[$identifier])) {
      return $this->repositories[$identifier];
    }
    $em = $this->getEm();
    $repository = $em->getRepository('AcmeTaskBundle:' . $identifier);
    return $repository;
  }
  
  public function newAction(Request $request) {
    return $this->redirect(
      $this->generateUrl(
        'acme_task_show',
        array()
      )
    );
  }
  
  public function showAction() {
    $tasks = $this->getRepository('Task')->findAllOrderedByDescription();

    $task = $this->getNewTask();
    $form = $this->createForm(new TaskType(), $task);

    $forms = array();
    foreach ($tasks as $oneTask) {
      $forms[] = $this->createForm(new TaskType, $oneTask)->createView();

    }
//    $forms[] = $form->createView();

    return $this->render('AcmeTaskBundle:Task:new.html.twig',
      array(
        'forms' => $forms
      )
    );
  }

  /**
   * @param $taskDb
   * @param $reqTask
   */
  protected function update($taskDb, $reqTask) {
    $taskDb->setDescription($reqTask->getDescription());
    $taskDb->setPos($reqTask->getPos());
    $reqTags = $reqTask->getTags();
    $taskDbTagsCount = $taskDb->getTags()->count();
    if (empty($taskDbTagsCount)) {
      foreach($reqTask->getTags() as $key => $tag) {
        $taskDb->addTag($tag);
        $this->getEm()->persist($tag);
      }
    } else {
      $tagsInTaskDb = array();
      foreach($taskDb->getTags() as $key => $tag) {
        $name = $reqTags[$key]->getName();
        $tagsInTaskDb[] = $name;
        $tag->setName($name);
        $this->getEm()->persist($tag);
      }
      if (sizeof($reqTags) > $taskDbTagsCount) {
        $start = $taskDbTagsCount;
        $end = sizeof($reqTags);
        for ($i = $start; $i < $end; $i++) {
          if (!in_array($reqTags[$i]->getName(), $tagsInTaskDb)) {
            $tag = new Tag();
            $tag->setName($reqTags[$i]->getName());
            $this->getEm()->persist($tag);
            $taskDb->addTag($tag);
          }
        }
      }
    }
    $this->getEm()->persist($taskDb);
    $this->getEm()->flush();
  }
  
  public function strategy($form) {
    $reqTask = $form->getData();
//      \Doctrine\Common\Util\Debug::dump($reqTask);
    $taskDb = $this->getRepository('Task')->findOneById($reqTask->getId());
    if (!empty($taskDb)) {
      $this->update($taskDb, $reqTask);
    }
    return $this->redirect(
      $this->generateUrl(
        'acme_task_new',
        array()
      )
    );
  }
  
  public function formAction(Request $request)
  {
    $task = $this->getNewTask();
    // end dummy code

//    $form = $this->createForm(new TaskType(), $task);
//    $tasks = $this->getRepository('Task')->findAllOrderedByDescription();
    
    
//    \Doctrine\Common\Util\Debug::dump($tasks);
//    $arr = $tasks->getTags();
//    \Doctrine\Common\Util\Debug::dump($arr);

//    \Doctrine\Common\Util\Debug::dump($tasks);
    $form = $this->createForm(new TaskType(), $task);
    $form->handleRequest($request);



    if ($form->isValid()) {
      $this->strategy($form);
    }
//      $reqTask = $form->getData();
////      \Doctrine\Common\Util\Debug::dump($reqTask);
//      $taskDb = $this->getRepository('Task')->findOneById($reqTask->getId());
////      \Doctrine\Common\Util\Debug::dump($task);
//      if (!empty($taskDb)) {
//
//        var_dump($reqTask->getPos());
//        // update $task
//        $taskDb->setDescription($reqTask->getDescription());
//        $taskDb->setPos($reqTask->getPos());
//        $reqTags = $reqTask->getTags(); 
//        foreach($taskDb->getTags() as $key => $tag) {
//          $tag->setName($reqTags[$key]->getName());
//          $em->persist($tag);
//        }
//        $em->persist($taskDb);
//        $em->flush();
//      } else {
//        $reqTags = $reqTask->getTags();
//        \Doctrine\Common\Util\Debug::dump($reqTags);
//        foreach($task->getTags() as $key => $tag) {
//          $tag->setName($reqTags[$key]->getName());
//          $em->persist($tag);
////          $em->flush();
//        }
//        $em->persist($task);
//        $em->flush();
//      }
//      var_dump(empty($task));

      // ... maybe do some form processing, like saving the Task and Tag objects

    return $this->redirect(
      $this->generateUrl(
        'acme_task_show',
        array()
      )
    );
  }

  /**
   * @return Task
   */
  public function getNewTask()
  {
    $task = new Task();
    $task->setPos(0);
    $tag1 = new Tag();
    $tag1->name = 'tag1';
    $task->getTags()->add($tag1);
    $tag2 = new Tag();
    $tag2->name = 'tag2';
    $task->getTags()->add($tag2);

    return $task;
  }
}
