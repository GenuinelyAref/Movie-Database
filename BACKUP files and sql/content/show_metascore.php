<?php
$metascore = $find_rs['Metascore'];
// set background colour according to metascore rating
if ($metascore < 40) {
  // 0 - 39 metascores are coloured red
  $background_colour = "#FF0000";
}
elseif ($metascore > 60) {
  // 61 - 100 metascores are coloured green
  $background_colour = "#66CC33";
}
else {
  // 40 - 60 metascores are coloured yellow
  $background_colour = "#FFCC33";
}
?>


<div class="metascore_div" style="background-color: <?php echo $background_colour; ?>;">
    <span class="metascore"><?php echo $find_rs['Metascore']; ?></span>
</div>
