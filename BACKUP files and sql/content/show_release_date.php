<!-- release date -->
<img class="content_icon" title="Release Date" src="images/calendar_icon.png">

<?php
$release_date = date("F j, Y", strtotime($find_rs['Release Date']));
echo "<strong>Released </strong>".$release_date;
?>
