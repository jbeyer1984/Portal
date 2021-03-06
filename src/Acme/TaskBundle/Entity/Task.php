<?php

// src/Acme/TaskBundle/Entity/Task.php
namespace Acme\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Acme\TaskBundle\Entity\TaskRepository")
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
   * @ORM\Column(type="integer")
   */
  protected $pos;
  
  /**
   * @ORM\Column(type="string", length=100, unique=true)
   */
  protected $description;

  /**
   * @ORM\ManyToMany(targetEntity="Tag", mappedBy="tasks", cascade={"persist"})
   */
  protected $tags;

  public function __construct()
  {
    $this->tags = new ArrayCollection();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function addTag(Tag $tag)
  {
    $tag->addTask($this);
    $this->tags->add($tag);
  }

  public function removeTag(Tag $tag)
  {
    $this->tags->removeElement($tag);
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }
  
  public function getPos() {
    return $this->pos;
  }

  public function setPos($pos) {
    $this->pos = $pos;
  }

  public function getTags()
  {
    return $this->tags;
  }

  public function __toString()
  {
    return 'Task';
  }
}