<?php

// src/Acme/PortalBundle/Entity/Tag.php
namespace Acme\PortalBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @UniqueEntity("name")
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Acme\PortalBundle\Entity\TagRepository")
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
   * @ORM\Column(type="string", length=100, unique=true)
   */
  public $name;

  /**
   * @ORM\ManyToMany(targetEntity="Article", inversedBy="name", cascade={"persist"})
   * @ORM\JoinTable(
   *     name="ArticleTag",
   *     joinColumns={
   *         @ORM\JoinColumn(
   *             name="tag_id",
   *             referencedColumnName="id",
   *             nullable=false
   *         )
   *     },
   *     inverseJoinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false)}
   * )
   */
  protected $articles;

  public function addArticle(Article $article)
  {
    if (!$this->articles->contains($article)) {
      $this->articles->add($article);
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
    return 'Article';
  }

}
