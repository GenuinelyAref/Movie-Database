<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $double_director = $_SESSION['Double_Director'];

  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($double_director == "no") {
      $director_ID_1 = mysqli_real_escape_string($dbconnect, $_POST['director']);
      $director_ID_2 = "unknown";
    }
    else {
      $director_ID_1 = mysqli_real_escape_string($dbconnect, $_POST['co-diretor-one']);
      $director_ID_2 = mysqli_real_escape_string($dbconnect, $_POST['co-diretor-two']);
    }
    $directors_array = array($director_ID_1, $director_ID_2);

    // create list of variables
    /*
    list($director_ID_one, $director_ID_two) = $directors_array;
    */

    $_SESSION['Add_Movie']=$directors_array;
    header('Location: index.php?page=../admin/add_entry');

  } // end submit button pushed if

} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Add a Movie</h1>
<p>
    To add a movie, first select the director(s), then press the 'next' button. If the
    director(s) is(are) not in the list, please choose the 'New Director' option. To quickly
    find an director, click in the box below and start typing their <b>last</b> name.
</p>


<form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/new_movie_part_two");?>">

    <?php
    if ($double_director == "no") {
      $number_of_directors = 1;
      // get directors from database
      $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
      $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
      $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

      ?>
      <!-- one director -->
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
      </div>
      <?php
    }
    else {
      $number_of_directors = 2;
      ?>
      <!-- two directors -->
      <h3>Choose your directors:</h3>
      <!-- co-director 1 -->
      <div>
        <b>Co-director 1:</b> &nbsp;
        <!-- dropdown menu -->
        <select name="co-diretor-one">
            <!-- default option (new director) -->
            <option value="unknown" selected>New Director</option>
            <!-- existing director -->
            <?php

            // get directors from database
            $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
            $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
            $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

            do {
              ?>
              <option value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            } // end of director options 'do'
            while ($all_directors_rs=mysqli_fetch_assoc($all_directors_query))
             ?>
        </select>
      </div>

      </br>
      <!-- co-director 2 -->
      <div>
        <b>Co-director 2:</b> &nbsp;
        <!-- dropdown menu -->
        <select name="co-diretor-two">
            <!-- default option (new director) -->
            <option value="unknown" selected>New Director</option>
            <!-- existing director -->
            <?php

            // get directors from database
            $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
            $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
            $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

            do {
              ?>
              <option value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            } // end of director options 'do'
            while ($all_directors_rs=mysqli_fetch_assoc($all_directors_query))
             ?>
        </select>
      </div>

      </br>
      <?php
    }
    ?>
    &nbsp;
    <input class="short" type="submit" name="director_one" value="Next..."/>
</form>
&nbsp;
