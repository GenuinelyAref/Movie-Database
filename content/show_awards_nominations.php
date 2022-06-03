<?php
// Nominations
$nominations = $find_rs['Nominations'];
if ($nominations > 1) {
  ?>
  <b><?php echo $nominations; ?></b> Nominations
  <?php
}
elseif ($nominations == 1) {
  ?>
  <b>1</b> Nomination
  <?php
}
else {
  ?>
  <b>No</b> Nominations
  <?php
}

// join nominations and awards on same line
echo " and ";

// Awards
$awards = $find_rs['Awards'];
if ($awards > 1) {
  ?>
  <b><?php echo $awards; ?></b> Awards
  <?php
}
elseif ($awards == 1) {
  ?>
  <b>1</b> Award
  <?php
}
else {
  ?>
  <b>no</b> Awards
  <?php
}
?>
