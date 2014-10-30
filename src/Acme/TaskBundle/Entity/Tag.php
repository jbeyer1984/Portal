<?php

// src/Acme/TaskBundle/Entity/Tag.php
namespace Acme\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tagg")
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

  /**
   * @ORM\ManyToMany(targetEntity="Task", inversedBy="name", cascade={"persist"})
   * @ORM\JoinTable(
   *     name="xxx",
   *     joinColumns={
   *         @ORM\JoinColumn(
   *             name="tag_id",
   *             referencedColumnName="id",
   *             nullable=false
   *         )
   *     },
   *     inverseJoinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false)}
   * )
   */
  protected $tasks;

  public function addTask(Task $task)
  {
    if (!$this->tasks->contains($task)) {
      $this->tasks->add($task);
    }    
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  public function __toString()
  {
    return 'Task';
  }

}
