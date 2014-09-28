<?php

// Acme/TaskBundle/Entity/Task.php

namespace Acme\TaskBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  
  /**
   * @Assert\NotBlank()
   * @ORM\Column(type="string", length=100)
   */
  public $task;

  /**
   * @Assert\NotBlank()
   * @Assert\Type("\DateTime")
   * @ORM\Column(type="datetime", name="dueDate")
   */
  protected $dueDate;

  public function getTask()
  {
    return $this->task;
  }

  public function setTask($task)
  {
    $this->task = $task;
  }

  public function getDueDate()
  {
    return $this->dueDate;
  }

  public function setDueDate(\DateTime $dueDate = null)
  {
    $this->dueDate = $dueDate;
  }
}