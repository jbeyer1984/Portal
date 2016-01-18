<?php

namespace Acme\PortalBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
   * @ORM\ManyToMany(targetEntity="Article", inversedBy="name")
   * @ORM\JoinTable(
   *     name="ArticleTag",
   *     joinColumns={
   *         @ORM\JoinColumn(
   *             name="tag_id",
   *             referencedColumnName="id",
   *             nullable=false
   *         )
   *     },
   *     inverseJoinColumns={
   *        @ORM\JoinColumn(
   *             name="article_id", 
   *             referencedColumnName="id", 
   *             nullable=false
   *        )
   *     }
   * )
   */
  protected $articles;

  public function __construct()
  { 
    $this->articles = new ArrayCollection();
  }

  public function addArticle(Article $article)
  {
    if (!$this->articles->contains($article)) {
      $this->articles->add($article);
    }    
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
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

  /**
   * @return mixed
   */
  public function getArticles()
  {
    return $this->articles;
  }

}
