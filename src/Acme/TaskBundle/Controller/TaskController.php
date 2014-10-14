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
  public function indexAction()
  {
    return $this->render('AcmeTaskBundle:Default:index.html.twig', array('name' => 'test'));
  }
  
  public function formAction(Request $request)
  {
    $task = new Task();
    $task->setOrder(0);
    // dummy code - this is here just so that the Task has some tags
    // otherwise, this isn't an interesting example
    $tag1 = new Tag();
    $tag1->name = 'tag1';
    $task->getTags()->add($tag1);
    $tag2 = new Tag();
    $tag2->name = 'tag2';
    $task->getTags()->add($tag2);
    // end dummy code

//    $form = $this->createForm(new TaskType(), $task);

    $em = $this->getDoctrine()->getManager();
    $taskRepository = $em->getRepository('AcmeTaskBundle:Task');
    $tasks = $taskRepository->findAllOrderedByDescription();
    
    $form = $this->createForm(new TaskType(), $task);
//    \Doctrine\Common\Util\Debug::dump($tasks);
//    $arr = $tasks->getTags();
//    \Doctrine\Common\Util\Debug::dump($arr);

//    \Doctrine\Common\Util\Debug::dump($tasks);

    $form->handleRequest($request);
    
    
    if ($form->isValid()) {
      $reqTask = $form->getData();
      \Doctrine\Common\Util\Debug::dump($reqTask);
      $task = $taskRepository->findOneById($reqTask->getId());
      if (!empty($task)) {

        var_dump($reqTask->getOrder());
        // update $task
        $task->setDescription($reqTask->getDescription());
        $task->setOrder($reqTask->getOrder());
        $reqTags = $reqTask->getTags(); 
        foreach($task->getTags() as $key => $tag) {
          $tag->setName($reqTags[$key]->getName());
          $em->persist($task);
          $em->flush();
        }
      }
//      var_dump(empty($task));

      // ... maybe do some form processing, like saving the Task and Tag objects
    }

//    $em = $this->getDoctrine()->getManager();
//    $tasks = $em->getRepository('AcmeTaskBundle:Task');
    
//    $tasks->findAllOrderedByDescription();

//    $forms = array($this->createForm(new TaskType, $task)->createView());
    $forms = array();
    foreach ($tasks as $oneTask) {
      $forms[] = $this->createForm(new TaskType, $oneTask)->createView();

    }

    return $this->render('AcmeTaskBundle:Task:new.html.twig', array(
//      'form' => $form->createView(),
      'forms' => $forms
    ));
  }
}
