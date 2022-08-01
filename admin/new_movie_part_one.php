<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get values from form
    $double_director = mysqli_real_escape_string($dbconnect, $_POST['double_director']);
    $_SESSION['Double_Director']=$double_director;
    header('Location: index.php?page=../admin/new_movie_part_two');

  } // end submit button pushed if


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<!-- progress bar NOT SHOWN - commented out -->
<!--
<div class="progress_divs">
  <div class="progress_bar_text">step 1/3</div>
  <div class="first_bar progress_rectangle_current"></div>
  <div class="second_bar progress_rectangle_incomplete"></div>
  <div class="third_bar progress_rectangle_incomplete"></div>
</div>
-->

<h1>Add a Movie</h1>
<p>
    Please start by choosing the number of directors associated with your movie.
    You can pick between one or two directors. Note that if you choose two directors the
    order doesn't matter; they are given equal co-director roles in the database. </br></br>
    <i>(If this presents an issue, please contact the website adminstrator directly.)</i>
</p>

<?php

// initialise double director option
$double_director = "unknown";

?>

<form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/new_movie_part_one");?>">
  <h3>How many directors worked on the movie?</h3>
    <div>
      <input type="radio" name="double_director" value="no"/> One director
      </br>
      </br>
      <input type="radio" name="double_director" value="yes"/> Two co-directors
      <!-- &nbsp; -->
    </div>
  </br>
    <input class="short" type="submit" name="director_two" value="Next..."/>
</form>


&nbsp;
