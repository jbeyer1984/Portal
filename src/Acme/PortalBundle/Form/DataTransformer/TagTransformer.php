<?php

namespace Acme\PortalBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
  /**
   * Transforms the Document's value to a value for the form field
   */
  public function reverseTransform($tag)
  {
    if (!$tag) {
      $tag = array(); // default value
    }

    return array('responsive', 'cms'); // concatenate the tags to one string
  }

  /**
   * Transforms the value the users has typed to a value that suits the field in the Document
   */
  public function transform($tag)
  {
    if (!$tag) {
      $tag = ''; // default
    }
    
    $tag = 'responsive;cms';

    return array_filter(array_map('trim', explode(';', $tag)));
    // 1. Split the string with commas
    // 2. Remove whitespaces around the tags
    // 3. Remove empty elements (like in "tag1,tag2, ,,tag3,tag4")
  }
}