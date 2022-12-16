<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $movie_ID = $_REQUEST['ID'];

  $deletemovieconfirm_sql = "SELECT * FROM `movie` WHERE `ID` = $movie_ID";
  $deletemovieconfirm_query = mysqli_query($dbconnect, $deletemovieconfirm_sql);
  $deletemovieconfirm_rs = mysqli_fetch_assoc($deletemovieconfirm_query);
  ?>

  <h2>Delete Movie - Confirm</h2>

  <p>Are you sure you want to delete the following movie?
    <br/>
    <i><?php echo $deletemovieconfirm_rs['Movie']; ?></i>
  </p>

  <p>
    <span class="delete-button">
      <a class="delete-text" href="index.php?page=../admin/deletemovie&ID=<?php echo $movie_ID;?>">
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
