<?php

// check user is logged in
if (isset($_SESSION['admin'])) {


  $directors_array = $_SESSION['Add_Movie'];

  list($director_ID_one, $director_ID_two) = $directors_array;

  if ($director_ID_two == "none") {
    $number_of_directors = 1;
  }
  else {
    $number_of_directors = 2;
  }

  // Get genre list from database
  $all_genres_sql = "SELECT * FROM `genre_movie` ORDER BY `Genre` ASC";
  $all_genres = autocomplete_list($dbconnect, $all_genres_sql, 'Genre');

  // initialise form variables for quote
  // must-fill fields
  $movie = "";
  $release_date = "";
  $certificate = "";
  $genre_1 = "";
  $duration = "";
  $metascore = "";
  $synopsis = "";

  // optional fields
  $genre_2 = "";
  $genre_3 = "";
  $gross = "";
  $awards = "";
  $nominations = "";

  // initialise tag IDs
  $genre_1_ID = $genre_2_ID = $genre_3_ID = 0;

  $has_errors = "no";

  // set up error fields / visibility
  $movie_error = $release_date_error = $certificate_error = $genre_1_error = $duration_error = $synopsis_error =  "no-error";
  $movie_field = $release_date_field = $certificate_field = $genre_1_field = $duration_field = $synopsis_field = "form-ok";

  // LEFT HERE ^^^^
  // LEFT HERE
  // LEFT HERE
  // LEFT HERE
  // LEFT HERE

    $metascore = mysqli_real_escape_string($dbconnect, $_POST['metascore']);
    if (!is_numeric($metascore) || $metascore < 0 || $metascore > 100) {
      $has_errors = "yes";
      $metascore_error = "error-text";
      $metascore_field = "form-error";

} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Add a Movie</h1>
<p>
    Fill out the details for your movie.
</p>

<form autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/add_entry");?>">
    <!-- TEMPLATE error message -->
    <div class="<?php echo $template_error; ?>">
        This field can't be blank
    </div>

    <!-- TEMPLATE -->
    <div class="input-text-box"> <!-- class not added in CSS yet -->
        <input type="text" class="add-field <?php echo $template_field; ?>" value="<?php echo $template; ?>" name="template" placeholder="Template" />
      <!-- Metascore input error message -->
      <div class="error-8 <?php echo $metascore_error; ?>">
          This field can't be blank
      </div>

      <!-- Metascore input box -->
      <div class="metascore-box">
        <img style="height: 39px; width: auto;" title="Metacritic Logo" src="images/metacritic-4.png">
        <input style="vertical-align: top; width: 120px;" type="text" class="<?php echo $metascore_field; ?>" value="<?php echo $metascore; ?>" name="metascore" placeholder="Metascore" />
        <span style="font-size: 25px; vertical-align: super;">&nbsp;/100</span>
      </div>
    </div>


</form>
