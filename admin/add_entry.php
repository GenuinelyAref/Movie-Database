<?php

// check user is logged in
if (isset($_SESSION['admin'])) {


  $directors_array = $_SESSION['Add_Movie'];

  list($director_ID_one, $director_ID_two) = $directors_array;

  if ($director_ID_two == "unknown") {
    echo "1 director was chosen, with the ID $director_ID_one";
  }
  else {
    echo "2 directors were chosen, with the IDs $director_ID_one and $director_ID_two";
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
  $synopsis = "";

  // optional fields
  $second_director_ID = "";
  $genre_2 = "";
  $genre_3 = "";
  $metascore = "";
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


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>
