<?php

// src/Acme/PortalBundle/Entity/Article.php
namespace Acme\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="Acme\PortalBundle\Entity\ArticleRepository")
 */
class Article
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
   * @ORM\ManyToMany(targetEntity="Tag", mappedBy="articles", cascade={"persist"})
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
    $tag->addArticle($this);
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
    return 'Article';
  }
}