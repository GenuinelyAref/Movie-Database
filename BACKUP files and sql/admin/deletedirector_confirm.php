<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $director_ID = $_REQUEST['directorID'];

  $deletedirector_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = '$director_ID'";
  $deletedirector_query = mysqli_query($dbconnect, $deletedirector_sql);
  $deletedirector_rs = mysqli_fetch_assoc($deletedirector_query);

  $full_name = $deletedirector_rs['First']." ".$deletedirector_rs['Middle']." ".$deletedirector_rs['Last'];

  //SELECT * FROM `movie` WHERE `DirectorID_1` = 98 AND `DirectorID_2` = 168

  // check for movies with this director
  $director_movies_sql = "SELECT * FROM `movie` WHERE `DirectorID_1` = '$director_ID' OR `DirectorID_2` = '$director_ID'";
  $director_movies_query = mysqli_query($dbconnect, $director_movies_sql);
  //$director_movies_rs = mysqli_fetch_assoc($director_movies_query);
  $director_movies_count = mysqli_num_rows($director_movies_query);

  $director_movies_many_sql = "SELECT * FROM `movie`
  WHERE (`DirectorID_1` = '$director_ID' AND `DirectorID_2` != '$director_ID' AND `DirectorID_2` != 0)
  OR (`DirectorID_1` != '$director_ID' AND `DirectorID_2` = '$director_ID') ";
  $director_movies_many_query = mysqli_query($dbconnect, $director_movies_many_sql);
  $director_movies_many_count = mysqli_num_rows($director_movies_many_query);

  $movies_deleted = $director_movies_count - $director_movies_many_count;
  ?>

  <h2>Delete Director - Confirm</h2>

  <p>Are you sure you want to delete the director <i><?php echo $full_name; ?></i>?</p>

  <?php
  if ($director_movies_count > 0) {
    ?>
    <div class="error">
      There are <?php echo $director_movies_count;?> movie(s) associated with the director <i><?php echo $full_name;?></i>, <?php echo $movies_deleted;?> of which will be deleted, as the remaining <?php echo $director_movies_many_count;?> movie(s) are associated with another director.
    </div>
    <?php
  }
  ?>

  <p>
    <span class="delete-button">
      <a class="delete-text" href="index.php?page=../admin/deletedirector&directorID=<?php echo $director_ID;?>">
        DELETE
      </a>
    </span>

    &nbsp; &nbsp; &nbsp;

    <span class="cancel-button">
      <a class="cancel-text" href="index.php?page=../admin/admin_panel">
        Cancel
      </a>
    </span>
  </p>
  <?php
} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>
