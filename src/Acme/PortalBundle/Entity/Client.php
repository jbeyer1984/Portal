<?php

// src/Acme/PortalBundle/Entity/Client.php
namespace Acme\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="Acme\PortalBundle\Entity\ClientRepository")
 */
class Client
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
   * @ORM\Column(type="string", length=100, unique=true, nullable=true)
   */
  protected $name;
  
  public $client_id;

  /**
   * @ORM\OneToMany(targetEntity="Article", mappedBy="client")
   */
  protected $articles;

  public function __construct()
  {
    $this->articles = new ArrayCollection();
  }

  public function addArticle(Article $article)
  {
    $article->setClient($this);
    $this->articles->add($article);
  }

  public function removeArticle(Article $article)
  {
    $this->articles->removeElement($article);
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getPos() {
    return $this->pos;
  }

  public function setPos($pos) {
    $this->pos = $pos;
  }

  public function getArticles()
  {
    return $this->articles;
  }

  public function __toString()
  {
    return 'Client';
  }
}