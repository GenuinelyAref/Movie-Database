<?php
$certificateID_db = $find_rs['CertificateID'];
$certificate_sql = "SELECT * FROM `certificate_movie`
WHERE `CertificateID` = '$certificateID_db'";
$certificate_query = mysqli_query($dbconnect, $certificate_sql);
$certificate_rs = mysqli_fetch_assoc($certificate_query);
?>
<img class="certficate_icons" title="<?php echo $certificate_rs['Certificate'];?>"
src="images/<?php echo $certificate_rs['Certificate'];?>.png">
