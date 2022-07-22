<!-- release date -->
<img class="calendar_icon" title="Release Date" src="images/calendar_icon.png">

<?php
$release_date = date("F j, Y", strtotime($find_rs['Release Date']));
echo $release_date;
?>
