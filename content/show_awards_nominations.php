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
    ?>
    <img src="images/nominations.png" class="content_icon" title="Nominations">
    <?php
    echo $nominations." nomination".$nominations_add_s."<br>";
}
if ($awards > 0) {
    ?>
    <img src="images/awards.png" class="content_icon" title="Nominations">
    <?php
    echo $awards." award".$awards_add_s;
}
?>
