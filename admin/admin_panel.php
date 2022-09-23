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

    /*if ($director_ID == "unknown") {
      header('Location: index.php?page=../admin/admin_panel');
    }*/

    if ($director_ID != "unknown") {
      header('Location: index.php?page=director&directorID='.$director_ID);
    }

  } // end submit button pushed if


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Admin Panel</h1>

<h2>Movies:</h2>
<p>
    To <a href="index.php?page=../admin/new_movie_part_one">add a movie</a>, use the
    preceding link or the '+' symbol at the top right of the page.
</p>
<p>
    Movies can edited / deleted by searching for a movie and then clicking on
    the 'edit' / 'delete' icons at the bottom right of each movie. If you
    don't see icons to edit / delete movies, it means that you are logged out.
</p>

<!-- horizontal line break -->
<hr/>

<h2>Directors:</h2>

<p>
  Either <a href="index.php?page=../admin/add_director">add a director</a> or choose a
  director from the dropdown box below to edit / delete an existing director.
</p>

<form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/admin_panel");?>">
    <div>
      <b>Movie Director:</b> &nbsp;

      <!-- dropdown menu -->
      <select name="director">
          <!-- default (dummy) option - not visible in dropdown -->
          <option value="unknown" selected style="display: none;">Choose..</option>

          <!-- existing directors -->
          <?php
          do {

            // concatenate direct names into full name (first + middle + last)
            $director_full = $all_directors_rs['Last'].", ".$all_directors_rs['First']." ".$all_directors_rs['Middle'];

            ?>

            <option value="<?php echo $all_directors_rs['DirectorID'];?>">
              <?php echo $director_full; ?>
            </option>

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
