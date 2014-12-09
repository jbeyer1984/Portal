<?php

class KartesischArtikel {
  public $arrayKartesisch = array();
  public $size = 0;
  
  public function init($arr) {
    $forward = true;
    $root = true;
    $this->size = sizeof($arr);
    $this->calc($arr, $forward, $root);
    $this->arrayKartesisch = array_unique($this->arrayKartesisch);
  }
  
  public function getCount() {
    return sizeof($this->arrayKartesisch) - $this->size;
  }
  
  public function calc($array, $forward, $root) {
//    var_dump($array);
//    array_shift($array);
//    var_dump($array);
//    for ($i = $lengthArray; $i > 0; $i--) {
    if (empty($array)) {
      return;
    }
    if ($forward) {
      $add = +1;
      $begin = 0;
      if (!$root) {
        array_shift($array);
      }
      $lengthArray = sizeof($array);
      $end = $lengthArray;
    } else {
      $add = -1;
      $end = -1;
      if (!$root) {
        array_pop($array);
      }
      $lengthArray = sizeof($array);
      $begin = $lengthArray-1;
    }
    for ($j = $begin; $j != $end; $j += $add) {
      $str = '';
      $str .= $array[$j];
      for ($k = 0; $k < $lengthArray; $k++) {
        if ($j != $k) {
          $str .= ',' + $array[$k];
        }
      }
      $this->arrayKartesisch[] = $str;
    }
    $this->calc($array, false, false);
    $this->calc($array, true, false);
  }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>what</title>
  <link rel="stylesheet" href="css/main.css"/>
</head>
<body>
  <?php
    $cartesisArticle = new KartesischArtikel();
    $cartesisArticle->init(array(1,2,3,4,5));
    
    var_dump($cartesisArticle->arrayKartesisch);
    var_dump($cartesisArticle->getCount());
//    echo printArr($twoDimArr);
//    sorting($twoDimArr);
  ?>
</body>
</html>
