<?php

$mysqli = new mysqli('localhost', 'root', 'root', 'task2');
$sql = 'SELECT categories_id, parent_id FROM categories ORDER BY parent_id DESC, categories_id ASC';
$result = $mysqli->query($sql)->fetch_all(MYSQLI_NUM);
$result = NormalizeArray($result);

function NormalizeArray($arr) {
  $newArray = [];
  foreach ($arr as $row) {
    $newArray[$row[0]][] = $row[1];
  }
  return $newArray;
}

function DisplayArrayAsSpoiler($arr, $indent = 0) {
  foreach ($arr as $key => $value) {
    //node is array
    if (is_array($value)) {
      echo "<details style='margin-left: calc($indent * 0.5rem);'><summary>$key <span>[" . @count($value) . "]</span></summary>";
      DisplayArrayAsSpoiler($value, $indent + 1);
      echo "</details>";
    }
    // node is single value
    else {
      if ($value !== null) {
        echo "<option style='margin-left: calc($indent * 0.5rem);'>$key => $value</option>";
      }
      else {
        echo "<option style='margin-left: calc($indent * 0.5rem);'>$key</option>";
      }
    }
  }
}

foreach (array_keys($result) as $left_key) {
  $right_key = $result[$left_key][0];
  
  $result[$right_key]['children'][$left_key] = $result[$left_key]['children'];
  
  if ($result[$left_key] > 0 ) {
    unset($result[$left_key]);
  }
}

DisplayArrayAsSpoiler($result[0]['children']);