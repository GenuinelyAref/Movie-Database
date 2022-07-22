<?php
$nominations = $find_rs['Nominations'];
// if nominations are equal to 1, remove pluralising s
$nominations_add_s = "s";
if ($nominations == 1) {
  $nominations_add_s = "";
}

$awards = $find_rs['Awards'];
// if awards are equal to 1, remove pluralising s
$awards_add_s = "s";
if ($awards == 1) {
  $awards_add_s = "";
}

// Nominations
if ($nominations > 0) {
  if ($awards > 0) {
    echo $nominations." nomination".$nominations_add_s." and ".$awards." award".$awards_add_s;
  }
  else {
    echo $nominations." nomination".$nominations_add_s;
  }
}
else {
  if ($awards > 0) {
    echo $awards." award".$awards_add_s;
  }
}
?>
