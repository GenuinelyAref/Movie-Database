<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $movie_ID = $_REQUEST['ID'];

  // delete movie
  $deletemovie_sql = "DELETE FROM `movie` WHERE `movie`.`ID` = '$movie_ID'";
  $deletemovie_query = mysqli_query($dbconnect, $deletemovie_sql);

  ?>

  <h1>Delete Success</h1>

  <p>The movie has been deleted</p>

  <?php
} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>
