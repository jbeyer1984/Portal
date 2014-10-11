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
    $tasks = $em->getRepository('AcmeTaskBundle:Task')
      ->findAllOrderedByDescription();

    $form = $this->createForm(new TaskType(), $task);
//    \Doctrine\Common\Util\Debug::dump($tasks);
//    $arr = $tasks->getTags();
//    \Doctrine\Common\Util\Debug::dump($arr);
//    foreach ($tasks as $oneTask) {
//      \Doctrine\Common\Util\Debug::dump($oneTask->getTags());
//      
//    }

//    \Doctrine\Common\Util\Debug::dump($tasks);

    $form->handleRequest($request);

    if ($form->isValid()) {
      $formReq = $form->getData();
      $em = $this->getDoctrine()->getManager();
      $em->persist($formReq);
      $em->flush();
      // ... maybe do some form processing, like saving the Task and Tag objects
    }

    return $this->render('AcmeTaskBundle:Task:new.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}
