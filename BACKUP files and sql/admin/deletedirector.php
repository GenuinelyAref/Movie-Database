<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $director_ID = $_REQUEST['directorID'];

  //SELECT * FROM `movie` WHERE `DirectorID_1` = 98 AND `DirectorID_2` = 168

  // check for movies with ONLY this director
  $director_movies_one_sql = "SELECT * FROM `movie` WHERE `DirectorID_1` = '$director_ID' AND `DirectorID_2` = 0";
  $director_movies_one_query = mysqli_query($dbconnect, $director_movies_one_sql);
  $director_movies_one_count = mysqli_num_rows($director_movies_one_query);
  if ($director_movies_one_count > 0) {
    $delete_movies_one_sql = "DELETE FROM `movie` WHERE `DirectorID_1` = '$director_ID' AND `DirectorID_2` = 0;";
    $delete_movies_one_query = mysqli_query($dbconnect, $delete_movies_one_sql);
  }

  // check for movies with this director and another director in 2nd ID column
  $director_movies_first_sql = "SELECT * FROM `movie` WHERE `DirectorID_1` = '$director_ID' AND `DirectorID_2` != '$director_ID' AND `DirectorID_2` != 0";
  $director_movies_first_query = mysqli_query($dbconnect, $director_movies_first_sql);
  $director_movies_first_rs = mysqli_fetch_assoc($director_movies_first_query);
  $director_movies_first_count = mysqli_num_rows($director_movies_first_query);
  if ($director_movies_first_count > 0) {
    do {
      $director_two = $director_movies_first_rs['DirectorID_2'];
      $movie_ID = $director_movies_first_rs['ID'];
      $delete_movies_first_sql = "UPDATE `movie` SET `DirectorID_1` = '$director_two', `DirectorID_2` = '0' WHERE `movie`.`ID` = '$movie_ID'";
      $delete_movies_first_query = mysqli_query($dbconnect, $delete_movies_first_sql);
    } while ($director_movies_first_rs=mysqli_fetch_assoc($director_movies_first_query));
  }

  // check for movies with this director and another director in 1st ID column
  $director_movies_second_sql = "SELECT * FROM `movie` WHERE `DirectorID_1` != '$director_ID' AND `DirectorID_2` = '$director_ID'";
  $director_movies_second_query = mysqli_query($dbconnect, $director_movies_second_sql);
  $director_movies_second_rs = mysqli_fetch_assoc($director_movies_second_query);
  $director_movies_second_count = mysqli_num_rows($director_movies_second_query);
  if ($director_movies_second_count > 0) {
    do {
      $movie_ID = $director_movies_second_rs['ID'];
      $delete_movies_second_sql = "UPDATE `movie` SET `DirectorID_2` = '0' WHERE `movie`.`ID` = '$movie_ID'";
      $delete_movies_second_query = mysqli_query($dbconnect, $delete_movies_second_sql);
    } while ($director_movies_second_rs=mysqli_fetch_assoc($director_movies_second_query));
  }

  // delete director
  $delete_director_sql = "DELETE FROM `director_movie` WHERE `director_movie`.`DirectorID` = '$director_ID'";
  $delete_director_query = mysqli_query($dbconnect, $delete_director_sql);

  ?>
  <h1>Delete Success</h1>

  <p>The director and any (solely) associated movies have been deleted</p>
  <?php

} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>
