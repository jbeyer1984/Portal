<?php

// src/Acme/TaskBundle/Entity/Tag.php
namespace Acme\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=100)
   */
  public $name;

  public function addTask(Task $task)
  {
    if (!$this->tasks->contains($task)) {
      $this->tasks->add($task);
    }    
  }

}
