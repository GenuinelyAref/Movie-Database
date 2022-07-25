<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  // get directors from database
  $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
  $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
  $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

  // initialise director form
  $first = "";
  $middle = "";
  $last = "";

  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get values from form
    $director_ID = mysqli_real_escape_string($dbconnect, $_POST['director']);
    $_SESSION['Add_Movie']=$director_ID;
    header('Location: index.php?page=../admin/add_entry');

  } // end submit button pushed if


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Add a Movie</h1>
<p><i>
    To add a movie, first select the director, then press the 'next' button. If the
    director is not in the list, please choose the 'New Director' option. To quickly
    find an director, click in the box below and start typing their <b>last</b> name.
</i></p>

<form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/new_movie");?>">
    <div>
      <b>Movie Director:</b> &nbsp;

      <!-- dropdown menu -->
      <select name="director">
          <!-- default option (new director) -->
          <option value="unknown" selected>New Director</option>

          <!-- existing director -->
          <?php
          do {
            ?>

            <option value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>

            <?php
          } // end of director options 'do'

          while ($all_directors_rs=mysqli_fetch_assoc($all_directors_query))

           ?>


      </select>

      &nbsp;

      <input class="short" type="submit" name="movie_director" value="Next..."/>


    </div>
</form>

&nbsp;
