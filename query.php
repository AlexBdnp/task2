<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

$mysqli = new mysqli('localhost', 'root', 'root', 'task2');
$sql = 'SELECT parent_id, categories_id FROM categories ORDER BY parent_id ASC, categories_id ASC';

$result = ($mysqli->query($sql)->fetch_all(MYSQLI_NUM));

function NormalizeArray($arr) {
  $newArray = [];
  foreach ($arr as $row) {
    $newArray[$row[0]][] = $row[1];
  }
  return $newArray;
}

function getChildrenOf($children, $col) {
  global $result;
  echo "<details><summary>" . $children[$col] . " <span>[" . @count($result[$children[$col]]) . "]</span></summary>";

  // if child category has its own children
  if (isset($result[$children[$col]])) {
    getChildrenOf($result[$children[$col]], 0);
  }
  echo "</details>";
  
  // if child category has siblings
  for ($i = $col+1; $i < count($children); $i++) {
    echo "<details><summary>" . $children[$i] . " <span>[" . @count($result[$children[$i]]) . "]</span></summary>";
    if (isset($result[$children[$i]])) {
      getChildrenOf($result[$children[$i]], 0);
    }
    echo "</details>";
  
  }
}

$result = NormalizeArray($result);
getChildrenOf($result[0], 0);

