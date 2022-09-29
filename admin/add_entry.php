<?php

// check user is logged in
if (isset($_SESSION['admin'])) {


  $directors_array = $_SESSION['Add_Movie'];

  list($director_ID_one, $director_ID_two) = $directors_array;
  $director_1_ID = $director_ID_one;
  $director_2_ID = $director_ID_two;

  /*
  if ($director_2_ID == "none") {
    $number_of_directors = 1;
  }
  else {
    $number_of_directors = 2;
  }
  */

  // if at least one director is unknown (new), prepare country db list to use in autocomplete
  if ($director_1_ID == "unknown" || $director_2_ID == "unknown") {
    // Get country list from database
    $all_countries_sql = "SELECT * FROM `country_movie` ORDER BY `Country` ASC";
    $all_countries = autocomplete_list($dbconnect, $all_countries_sql, 'Country');
  }

  // both director ID's are the same (ie. 2 new directors)
  if ($director_1_ID == $director_2_ID) {
    // take details of both new directors

    // director 1 variables
    $url_one = "";
    $first_one = "";
    $middle_one = "";
    $last_one = "";
    $yob_one = "";
    $country_one = "";

    // director 2 variables
    $url_two = "";
    $first_two = "";
    $middle_two = "";
    $last_two = "";
    $yob_two = "";
    $country_two = "";

    // initialise ID's
    $country_one_ID = $country_two_ID = 0;

    // set up error fields/messages
    $url_one_error = $last_one_error = $first_one_error = $yob_one_error = $country_one_error = "no-error";
    $url_two_error = $last_two_error = $first_two_error = $yob_two_error = $country_two_error = "no-error";

    $url_one_field = $last_one_field = $first_one_field = $yob_one_field = $country_one_field = "form-ok";
    $url_two_field = $last_two_field = $first_two_field = $yob_two_field = $country_two_field = "form-ok";

  }
  // only one director is new (doesn't matter whether it's for one or two directors)
  elseif ($director_1_ID == "unknown" || $director_2_ID == "unknown") {
    // take details of a new director

    // director variables
    $url = "";
    $first = "";
    $middle = "";
    $last = "";
    $yob = "";
    $country = "";

    // initialise ID's
    $country_ID = 0;

    // set up error fields/messages
    $url_error = $last_error = $first_error = $yob_error = $country_error = "no-error";

    $url_field = $last_field = $first_field = $yob_field = $country_field = "form-ok";
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
  $gross = "";
  $synopsis = "";

  // optional fields
  $genre_2 = "";
  $genre_3 = "";
  $awards = "";
  $nominations = "";

  // initialise tag IDs
  $genre_1_ID = $genre_2_ID = $genre_3_ID = 0;

  $has_errors = "no";

  // set up error fields / visibility
  $movie_error = $release_date_error = $certificate_error = $genre_1_error = $duration_error = $metascore_error = $gross_error = $awards_error = $nominations_error = $synopsis_error =  "no-error";
  $movie_field = $release_date_field = $certificate_field = $genre_1_field = $duration_field = $metascore_field = $gross_field = $awards_field = $nominations_field = $synopsis_field = "form-ok";

  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // retrieve director info if there are two new directors
    if ($director_1_ID == $director_2_ID) {

      // retrieve director 1 entries
      $url_one = mysqli_real_escape_string($dbconnect, $_POST['url_one']);
      $first_one = mysqli_real_escape_string($dbconnect, $_POST['first_one']);
      $middle_one = mysqli_real_escape_string($dbconnect, $_POST['middle_one']);
      $last_one = mysqli_real_escape_string($dbconnect, $_POST['last_one']);
      $yob_one = mysqli_real_escape_string($dbconnect, $_POST['yob_one']);
      $country_one = mysqli_real_escape_string($dbconnect, $_POST['country_one']);

      // director 1 input validation

      // remove all illegal characters from the url
      $url_one = filter_var($url_one, FILTER_SANITIZE_URL);
      if (filter_var($url_one, FILTER_VALIDATE_URL) == false) {
        $has_errors = "yes";
        $url_one_error = "error-text";
        $url_one_field = "form-error";
      }

      // check first name is not blank
      if ($first_one == "") {
        $has_errors = "yes";
        $first_one_error = "error-text";
        $first_one_field = "form-error";
      }

      // check last name is not blank
      if ($last_one == "") {
        $has_errors = "yes";
        $last_one_error = "error-text";
        $last_one_field = "form-error";
      }

      // check year of birth is valid
      $current_year = date("Y");
      // note: 1873 is the year the world's first director (Alice Guy-Blache) was born
      if ($yob_one == "" || strval($yob_one) !== strval(intval($yob_one)) || $yob_one > $current_year || $yob_one < 1873) {
        $has_errors = "yes";
        $yob_one_error = "error-text";
        $yob_one_field = "form-error";
      }

      // check country is not blank
      if ($country_one == "") {
        $has_errors = "yes";
        $country_one_error = "error-text";
        $country_one_field = "form-error";
      }


      // retrieve director 2 entries
      $url_two = mysqli_real_escape_string($dbconnect, $_POST['url_two']);
      $first_two = mysqli_real_escape_string($dbconnect, $_POST['first_two']);
      $middle_two = mysqli_real_escape_string($dbconnect, $_POST['middle_two']);
      $last_two = mysqli_real_escape_string($dbconnect, $_POST['last_two']);
      $yob_two = mysqli_real_escape_string($dbconnect, $_POST['yob_two']);
      $country_two = mysqli_real_escape_string($dbconnect, $_POST['country_two']);

      // director 2 input validation

      // remove all illegal characters from the url
      $url_two = filter_var($url_two, FILTER_SANITIZE_URL);
      if (filter_var($url_two, FILTER_VALIDATE_URL) == false) {
        $has_errors = "yes";
        $url_two_error = "error-text";
        $url_two_field = "form-error";
      }

      // check first name is not blank
      if ($first_two == "") {
        $has_errors = "yes";
        $first_two_error = "error-text";
        $first_two_field = "form-error";
      }

      // check last name is not blank
      if ($last_two == "") {
        $has_errors = "yes";
        $last_two_error = "error-text";
        $last_two_field = "form-error";
      }

      // check year of birth is valid
      $current_year = date("Y");
      // note: 1873 is the year the world's first director (Alice Guy-Blache) was born
      if ($yob_two == "" || strval($yob_two) !== strval(intval($yob_two)) || $yob_two > $current_year || $yob_two < 1873) {
        $has_errors = "yes";
        $yob_two_error = "error-text";
        $yob_two_field = "form-error";
      }

      // check country is not blank
      if ($country_two == "") {
        $has_errors = "yes";
        $country_two_error = "error-text";
        $country_two_field = "form-error";
      }


      // get country ID's using get_ID function
      $country_1_ID = get_ID($dbconnect, 'country_movie', 'Country_ID', 'Country', $country_one);
      $country_2_ID = get_ID($dbconnect, 'country_movie', 'Country_ID', 'Country', $country_two);


    }

    // retrieve director info if there is one new director
    elseif ($director_1_ID == "unknown" || $director_2_ID == "unknown") {

      // director details
      $url = mysqli_real_escape_string($dbconnect, $_POST['url']);
      $first = mysqli_real_escape_string($dbconnect, $_POST['first']);
      $middle = mysqli_real_escape_string($dbconnect, $_POST['middle']);
      $last = mysqli_real_escape_string($dbconnect, $_POST['last']);
      $yob = mysqli_real_escape_string($dbconnect, $_POST['yob']);
      $country = mysqli_real_escape_string($dbconnect, $_POST['country']);

      // director 2 input validation

      // remove all illegal characters from the url
      $url = filter_var($url, FILTER_SANITIZE_URL);
      if (filter_var($url, FILTER_VALIDATE_URL) == false) {
        $has_errors = "yes";
        $url_error = "error-text";
        $url_field = "form-error";
      }

      // check first name is not blank
      if ($first == "") {
        $has_errors = "yes";
        $first_error = "error-text";
        $first_field = "form-error";
      }

      // check last name is not blank
      if ($last == "") {
        $has_errors = "yes";
        $last_error = "error-text";
        $last_field = "form-error";
      }

      // check year of birth is valid
      $current_year = date("Y");
      // note: 1873 is the year the world's first director (Alice Guy-Blache) was born
      if ($yob == "" || strval($yob) !== strval(intval($yob)) || $yob > $current_year || $yob < 1873) {
        $has_errors = "yes";
        $yob_error = "error-text";
        $yob_field = "form-error";
      }

      // check country is not blank
      if ($country == "") {
        $has_errors = "yes";
        $country_error = "error-text";
        $country_field = "form-error";
      }


      // get country ID's using get_ID function
      $countryID = get_ID($dbconnect, 'country_movie', 'Country_ID', 'Country', $country);

    }

    // get data from form
    $movie = mysqli_real_escape_string($dbconnect, $_POST['movie']);
    $certificate = mysqli_real_escape_string($dbconnect, $_POST['certificate']);
    $release_date = mysqli_real_escape_string($dbconnect, $_POST['release_date']);
    $synopsis = mysqli_real_escape_string($dbconnect, $_POST['synopsis']);
    $genre_1 = mysqli_real_escape_string($dbconnect, $_POST['Genre_1']);
    $genre_2 = mysqli_real_escape_string($dbconnect, $_POST['Genre_2']);
    $genre_3 = mysqli_real_escape_string($dbconnect, $_POST['Genre_3']);
    $duration = mysqli_real_escape_string($dbconnect, $_POST['duration']);
    $metascore = mysqli_real_escape_string($dbconnect, $_POST['metascore']);
    $gross = mysqli_real_escape_string($dbconnect, $_POST['gross']);
    $nominations = mysqli_real_escape_string($dbconnect, $_POST['nominations']);
    $awards = mysqli_real_escape_string($dbconnect, $_POST['awards']);

    // check data is valid

    // check movie field is not blank
    if ($movie == "") {
      $has_errors = "yes";
      $movie_error = "error-text";
      $movie_field = "form-error";
    }

    // check certificate field is not blank
    if ($certificate == 0) {
      $has_errors = "yes";
      $certificate_error = "error-text";
      $certificate_field = "form-error";
    }

    // check release date is not blank
    if ($release_date == "") {
      $has_errors = "yes";
      $release_date_error = "error-text";
      $release_date_field = "form-error";
    }


    // check 1st genre is not blank
    if ($genre_1 == "") {
      $has_errors = "yes";
      $genre_1_error = "error-text";
      $genre_1_field = "form-error";
    }

    // check metascore is not blank and between 0 and 100
    if ($metascore == "") {
      $has_errors = "yes";
      $metascore_error = "error-text";
      $metascore_field = "form-error";
      $metascore_error_message = "This field can't be blank";
    }
    elseif (strval($metascore) !== strval(intval($metascore)) || $metascore < 0 || $metascore > 100) {
      $has_errors = "yes";
      $metascore_error = "error-text";
      $metascore_field = "form-error";
      $metascore_error_message = "Invalid";
    }

    // check gross is not blank and between 0 and $5B
    if ($gross == "") {
      $has_errors = "yes";
      $gross_error = "error-text";
      $gross_field = "form-error";
      $gross_error_message = "This field can't be blank";
    }
    elseif (strval($gross) !== strval(intval($gross))) {
      $has_errors = "yes";
      $gross_error = "error-text";
      $gross_field = "form-error";
      $gross_error_message = "Enter an integer value";
    }
    elseif ($gross < 1) {
      $has_errors = "yes";
      $gross_error = "error-text";
      $gross_field = "form-error";
      $gross_error_message = "Min. gross $1M";
    }
    elseif ($gross > 5000) {
      $has_errors = "yes";
      $gross_error = "error-text";
      $gross_field = "form-error";
      $gross_error_message = "Max. gross $5B";
    }

    // check nominations is an integer 0 or over
    if ($nominations != "" && (strval($nominations) !== strval(intval($nominations)) || $nominations < 0)) {
      $has_errors = "yes";
      $nominations_error = "error-text";
      $nominations_field = "form-error";
      $nominations_error_message = "Positive integers only";
    }

    // check awards is an integer 0 or over
    if ($awards != "" && (strval($awards) !== strval(intval($awards)) || $awards < 0)) {
      $has_errors = "yes";
      $awards_error = "error-text";
      $awards_field = "form-error";
      $awards_error_message = "Positive integers only";
    }


    // check duration is not blank and under 4 hours
    if ($duration == "") {
      $has_errors = "yes";
      $duration_error = "error-text";
      $duration_field = "form-error";
      $duration_error_message = "This field can't be blank";
    }
    elseif (strval($duration) !== strval(intval($duration))) {
      $has_errors = "yes";
      $duration_error = "error-text";
      $duration_field = "form-error";
      $duration_error_message = "Enter an integer value";
    }
    elseif ($duration < 1) {
      $has_errors = "yes";
      $duration_error = "error-text";
      $duration_field = "form-error";
      $duration_error_message = "Min. duration 1 min";
    }
    elseif ($duration > 240) {
      $has_errors = "yes";
      $duration_error = "error-text";
      $duration_field = "form-error";
      $duration_error_message = "Max. duration 240 mins";
    }

    // check synopsis is not blank
    if ($synopsis == "") {
      $has_errors = "yes";
      $synopsis_error = "error-text";
      $synopsis_field = "form-error";
    }



    if ($has_errors != "yes") {

      // ADD NEW DIRECTOR(S) to DB

      // if there two new directors
      if ($director_1_ID == $director_2_ID) {

        // DIRECTOR 1: SQL to add director details to directors' DB
        $add_director_one_sql = "
        INSERT INTO `director_movie` (`DirectorID`, `First`, `Middle`, `Last`, `URL`, `YOB`, `CountryID`)
        VALUES (NULL, '$first_one', '$middle_one', '$last_one', '$url_one', '$yob_one', '$country_1_ID')
        ";
        // query to add director 1 to DB
        $add_director_one_query = mysqli_query($dbconnect, $add_director_one_sql);

        // get 1st director info from DB
        $find_director_one_sql = "SELECT * FROM `director_movie`
        ORDER BY `director_movie`.`DirectorID` DESC
        LIMIT 1"; // get last added director
        $find_director_one_query = mysqli_query($dbconnect, $find_director_one_sql);
        $find_director_one_rs = mysqli_fetch_assoc($find_director_one_query);

        // set 1st director ID to first new director's ID
        $director_1_ID = $find_director_one_rs['DirectorID'];


        // DIRECTOR 2: SQL to add director details to directors' DB
        $add_director_two_sql = "
        INSERT INTO `director_movie` (`DirectorID`, `First`, `Middle`, `Last`, `URL`, `YOB`, `CountryID`)
        VALUES (NULL, '$first_two', '$middle_two', '$last_two', '$url_two', '$yob_two', '$country_2_ID')
        ";
        // query to add director 2 to DB
        $add_director_two_query = mysqli_query($dbconnect, $add_director_two_sql);

        // get 2nd director info from DB
        $find_director_two_sql = "SELECT * FROM `director_movie`
        ORDER BY `director_movie`.`DirectorID` DESC
        LIMIT 1"; // get last added director
        $find_director_two_query = mysqli_query($dbconnect, $find_director_two_sql);
        $find_director_two_rs = mysqli_fetch_assoc($find_director_two_query);

        // set 1st director ID to first new director's ID
        $director_2_ID = $find_director_two_rs['DirectorID'];

      } // end if there two new directors


      // if there is one new director
      elseif ($director_1_ID == "unknown" || $director_2_ID == "unknown") {

        // SINGLE DIRECTOR: SQL to add director details to directors' DB
        $add_director_sql = "
        INSERT INTO `director_movie` (`DirectorID`, `First`, `Middle`, `Last`, `URL`, `YOB`, `CountryID`)
        VALUES (NULL, '$first', '$middle', '$last', '$url', '$yob', '$countryID')
        ";
        // query to add director to DB
        $add_director_query = mysqli_query($dbconnect, $add_director_sql);


        // get director info from DB
        $find_director_sql = "SELECT * FROM `director_movie`
        ORDER BY `director_movie`.`DirectorID` DESC
        LIMIT 1"; // get last added director
        $find_director_query = mysqli_query($dbconnect, $find_director_sql);
        $find_director_rs = mysqli_fetch_assoc($find_director_query);

        // assign new director to director 1/2 depending on which one was set as 'new director'
        if ($director_1_ID == "unknown") {
          $director_1_ID = $find_director_rs['DirectorID'];
        }
        else {
          $director_2_ID = $find_director_rs['DirectorID'];
        }

      } // end if there is one new director if statement

      // If there is only one existing director, set ID to 0
      if ($director_2_ID == "none") {
        $director_2_ID = 0;
      }


      // awards is not a compulsory field, so default to 0 if not specified
      if ($awards == "") {
        $awards = 0;
      }
      // nominations is not a compulsory field, so default to 0 if not specified
      if ($nominations == "") {
        $nominations = 0;
      }
      // gross input is taken in millions, multiple by 10^6 to give exact value in DB
      $gross = $gross * 1000000;

      // get certificate ID using get_ID function
      // $certficate IS ALREADY IN ITS ID FORMAT
      // $certificateID = get_ID($dbconnect, 'certificate_movie', 'CertificateID', 'Certficate', $certificate);

      // get genre ID's using get_ID function
      $genreID_1 = get_ID($dbconnect, 'genre_movie', 'GenreID', 'Genre', $genre_1);
      $genreID_2 = get_ID($dbconnect, 'genre_movie', 'GenreID', 'Genre', $genre_2);
      $genreID_3 = get_ID($dbconnect, 'genre_movie', 'GenreID', 'Genre', $genre_3);


      // add entry to database
      $addentry_sql = "INSERT INTO `movie` (`ID`, `Release Date`, `Movie`, `DirectorID_1`, `DirectorID_2`, `CertificateID`, `GenreID_1`, `GenreID_2`, `GenreID_3`, `Metascore`, `Gross`, `Awards`, `Nominations`, `Duration`, `Synopsis`) VALUES
      (NULL, '$release_date', '$movie', '$director_1_ID', '$director_2_ID', '$certificate', '$genreID_1', '$genreID_2', '$genreID_3', '$metascore', '$gross', '$awards', '$nominations', '$duration', '$synopsis')
      ";
      $addentry_query = mysqli_query($dbconnect, $addentry_sql);


      // get movie ID for next page
      $get_movie_sql = "SELECT * FROM `movie` WHERE `Movie` = '$movie'";
      $get_movie_query = mysqli_query($dbconnect, $get_movie_sql);
      $get_movie_rs = mysqli_fetch_assoc($get_movie_query);

      $movie_ID = $get_movie_rs['ID'];
      $_SESSION['Movie_Success']=$movie_ID;

      // Go to success page
      header('Location: index.php?page=movie_success');

    } // end add-entry to database if statement



  } // end submit form if statement


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

    <?php
    if ($director_1_ID == $director_2_ID) {
      // take details of both new directors
      ?>
      <!-- two new directors div -->
      <div class="new-director-forms">
        <!-- 1st director details div-->
        <div class="new-director-one-form">
          <h2>Director 1 details</h2>

          </br>

          <!-- TWO DIRECTORS (director 1): First name error message -->
          <div class="<?php echo $first_one_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 1): First name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing <?php echo $first_one_field; ?>" value="<?php echo $first_one; ?>" name="first_one" placeholder="First name" />
          </div>

          <!-- TWO DIRECTORS (director 1): Middle name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing" value="<?php echo $middle_one; ?>" name="middle_one" placeholder="Middle name" />
          </div>

          <!-- TWO DIRECTORS (director 1): Last name error message -->
          <div class="<?php echo $last_one_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 1): Last name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing <?php echo $last_one_field; ?>" value="<?php echo $last_one; ?>" name="last_one" placeholder="Last name" />
          </div>

          <!-- TWO DIRECTORS (director 1): URL error message -->
          <div class="<?php echo $url_one_error; ?>">
              Please enter a valid URL
          </div>

          <!-- TWO DIRECTORS (director 1): URL -->
          <div>
              <input type="text" class="new-director-long-width new-director-spacing <?php echo $url_one_field; ?>" value="<?php echo $url_one; ?>" name="url_one" placeholder="URL" />
          </div>

          <!-- TWO DIRECTORS (director 1): YOB error message -->
          <div class="<?php echo $yob_one_error; ?>">
              Enter a valid year
          </div>

          <!-- TWO DIRECTORS (director 1): YOB -->
          <div>
              <input type="text" maxlength="4" class="new-director-small-width new-director-spacing <?php echo $yob_one_field; ?>" value="<?php echo $yob_one; ?>" name="yob_one" placeholder="Born (year)" />
          </div>

          <!-- TWO DIRECTORS (director 1): Country error message -->
          <div class="<?php echo $country_one_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 1): Country -->
          <div class="autocomplete">
              <input type="text" id="country1" class="new-director-mid-width new-director-spacing <?php echo $country_one_field; ?>" value="<?php echo $country_one; ?>" name="country_one" placeholder="Country" />
          </div>
        </div> <!-- end of 1st director details div -->


        <!-- 2nd director details div -->
        <div class="new-director-two-form">
          <h2>Director 2 details</h2>

          </br>

          <!-- TWO DIRECTORS (director 2):  First name error message -->
          <div class="<?php echo $first_two_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 2): First name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing <?php echo $first_two_field; ?>" value="<?php echo $first_two; ?>" name="first_two" placeholder="First name" />
          </div>

          <!-- TWO DIRECTORS (director 2): Middle name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing" value="<?php echo $middle_two; ?>" name="middle_two" placeholder="Middle name" />
          </div>

          <!-- TWO DIRECTORS (director 2): Last name error message -->
          <div class="<?php echo $last_two_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 2): Last name -->
          <div>
              <input type="text" class="new-director-mid-width new-director-spacing <?php echo $last_two_field; ?>" value="<?php echo $last_two; ?>" name="last_two" placeholder="Last name" />
          </div>

          <!-- TWO DIRECTORS (director 2): URL error message -->
          <div class="<?php echo $url_two_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 2): URL -->
          <div>
              <input type="text" class="new-director-long-width new-director-spacing <?php echo $url_two_field; ?>" value="<?php echo $url_two; ?>" name="url_two" placeholder="URL" />
          </div>

          <!-- TWO DIRECTORS (director 2): YOB error message -->
          <div class="<?php echo $yob_two_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 2): YOB -->
          <div>
              <input type="text" maxlength="4" class="new-director-small-width new-director-spacing <?php echo $yob_two_field; ?>" value="<?php echo $yob_two; ?>" name="yob_two" placeholder="Born (year)" />
          </div>

          <!-- TWO DIRECTORS (director 2): Country error message -->
          <div class="<?php echo $country_two_error; ?>">
              This field can't be blank
          </div>

          <!-- TWO DIRECTORS (director 2): Country -->
          <div class="autocomplete">
              <input type="text" id="country2" class="new-director-mid-width new-director-spacing <?php echo $country_two_field; ?>" value="<?php echo $country_two; ?>" name="country_two" placeholder="Country" />
          </div>
        </div> <!-- end of 2nd director details div -->
      </div> <!-- end of two new directors div -->

      <hr style="opacity: 30%;margin: 50px 0px; height:1.5px; border-width:0; color:black; background-color:black">

      <?php
    }
    elseif ($director_1_ID == "unknown" || $director_2_ID == "unknown") {
      // take details of a new director
      ?>
      <h2>New director details:</h2>

      </br>

      <!-- ONE DIRECTOR: First name error message -->
      <div class="<?php echo $first_error; ?>">
          This field can't be blank
      </div>

      <!-- ONE DIRECTOR: First name -->
      <div>
          <input type="text" class="new-director-mid-width new-director-spacing <?php echo $first_field; ?>" value="<?php echo $first; ?>" name="first" placeholder="First name" />
      </div>

      <!-- ONE DIRECTOR: Middle name -->
      <div>
          <input type="text" class="new-director-mid-width new-director-spacing" value="<?php echo $middle; ?>" name="middle" placeholder="Middle name" />
      </div>

      <!-- ONE DIRECTOR: Last name error message -->
      <div class="<?php echo $last_error; ?>">
          This field can't be blank
      </div>

      <!-- ONE DIRECTOR: Last name -->
      <div>
          <input type="text" class="new-director-mid-width new-director-spacing <?php echo $last_field; ?>" value="<?php echo $last; ?>" name="last" placeholder="Last name" />
      </div>

      <!-- ONE DIRECTOR: URL error message -->
      <div class="<?php echo $url_error; ?>">
          Enter a valid URL
      </div>

      <!-- ONE DIRECTOR: URL -->
      <div>
          <input type="text" class="new-director-long-width new-director-spacing <?php echo $url_field; ?>" value="<?php echo $url; ?>" name="url" placeholder="URL" />
      </div>

      <!-- ONE DIRECTOR: YOB error message -->
      <div class="<?php echo $yob_error; ?>">
          Enter a valid year
      </div>

      <!-- ONE DIRECTOR: YOB -->
      <div>
          <input type="text" maxlength="4" class="new-director-small-width new-director-spacing <?php echo $yob_field; ?>" value="<?php echo $yob; ?>" name="yob" placeholder="Born (year)" />
      </div>

      <!-- Country error message -->
      <div class="<?php echo $country_error; ?>">
          This field can't be blank
      </div>

      <!-- ONE DIRECTOR: Country -->
      <div class="autocomplete">
          <input type="text" id="country" class="new-director-mid-width new-director-spacing <?php echo $country_field; ?>" value="<?php echo $country; ?>" name="country" placeholder="Country" />
      </div>

      <!-- horizontal line separator -->
      <hr style="opacity: 30%;margin: 40px 0px; height:1.5px; border-width:0; color:black; background-color:black">
      <?php
    }

    if ($director_2_ID == "none") {
      $director_2_ID = 0;
    }
    ?>

    <h2>New movie details:</h2>

    </br>

    <!-- add movie entry form starts here -->
    <div class="input-text-boxes">

      <!-- Movie error message -->
      <div style="margin-left: 43px;" class="error-1 <?php echo $movie_error; ?>">
          This field can't be blank
      </div>

      <!-- Movie field -->
      <div class="movie-name-box"> <!-- class added to control width of field -->
          <img src="images/popcorn.png" style="height: 39px; vertical-align: bottom;" alt="popcorn icon">
          <input type="text" style="width: 96%;" class="<?php echo $movie_field; ?>" value="<?php echo $movie; ?>" name="movie" placeholder="Movie Name..." />
      </div>

      <!-- certificate error message -->
      <div style="margin-left: 45px;" class="error-2 <?php echo $certificate_error; ?>">
          Please choose a certificate
      </div>

      <!-- label for certficate dropdown menu -->
      <!--
      <h3>Certficate: </h3>
      -->

      <!-- certficate dropdown menu -->
      <div class="certificate-box">
        <img src="images/certification.png" style="height: 39px; vertical-align: bottom;" alt="certification icon">
        <select style="width: 80%;" name="certificate" class="<?php echo $certificate_field; ?>">

            <!-- dummy option-->
            <option style="display: none;" value="0" selected>Certficate..</option>

            <!-- list of certficates -->
            <?php
            $all_certificates_sql = "SELECT * FROM `certificate_movie`";
            $all_certificates_query = mysqli_query($dbconnect, $all_certificates_sql);
            $all_certificates_rs = mysqli_fetch_assoc($all_certificates_query);
            do {
              // normal listing of certificates
              if ($all_certificates_rs['CertificateID'] != $certificate) {
                ?>
                <option value="<?php echo $all_certificates_rs['CertificateID'];?>"><?php echo $all_certificates_rs['Certificate'];?></option>
                <?php
              }
              // if certificate is chosen, have it selected
              else {
                ?>
                <option selected value="<?php echo $all_certificates_rs['CertificateID'];?>"><?php echo $all_certificates_rs['Certificate'];?></option>
                <?php
              }
            } // end of certificate options 'do'
            while ($all_certificates_rs=mysqli_fetch_assoc($all_certificates_query))
             ?>

        </select>
      </div>
      <!-- end of certficate dropdown menu -->

      <!-- Release date error message -->
      <div style="margin-right: 42px; text-align: right;" class="error-3 <?php echo $release_date_error; ?>">
          Select a date
      </div>

      <div class="release-date-box">
        <img style="height: 35px; width: auto; margin-left: 7px;" title="Release Date" src="images/calendar_icon.png">
        <span style="vertical-align: top;display: inline-flex;font-size: 15px;padding: 0px 3px 0px 3px;">Release<br>date:</span>
        <input value="<?php echo $release_date; ?>" class="<?php echo $release_date_field; ?>" type="date" name="release_date" min="1970-01-01" max="2022-06-30" style="display: inline; vertical-align: top;">
      </div>

      <!-- Genre 1 input error message -->
      <div style="margin-left: 63px;" class="error-4 <?php echo $genre_1_error; ?>">
          This field can't be blank
      </div>

      <!-- Genre 1 input -->
      <div class="genre-1-box">
          <img style="margin-left: 20px; height: 39px; width: auto;" title="Genre 1" src="images/pistol.png">
          <div class="autocomplete">
            <input id="genre1" style="max-width: 170px;" class="align-input-box <?php echo $genre_1_field; ?>" type="text" value="<?php echo $genre_1; ?>" name="Genre_1" placeholder="Genre (start typing)..." />
          </div>
      </div>
      <!-- -->

      <!-- Genre 2 input -->
      <div class="genre-2-box">
          <img style="margin-left: 20px; height: 39px; width: auto;" title="Genre 2" src="images/ufo.png">
          <div class="autocomplete">
            <input id="genre2" style="max-width: 170px;" class="align-input-box" type="text" value="<?php echo $genre_2; ?>" name="Genre_2" placeholder="2nd Genre (optional)..." />
          </div>
      </div>

      <!-- Genre 3 input -->
      <div class="genre-3-box">
          <img style="margin-left: 20px; height: 39px; width: auto;" title="Genre 3" src="images/ghost.png">
          <div class="autocomplete">
            <input id="genre3" style="max-width: 170px;" class="align-input-box" type="text" value="<?php echo $genre_3; ?>" name="Genre_3" placeholder="3rd Genre (optional)..." />
          </div>
      </div>


      <!-- Metascore input error message -->
      <div style="margin-left: 45px;" class="error-8 <?php echo $metascore_error; ?>">
          <?php
          if ($metascore_error_message == "Invalid") {
            ?>
            Invalid - <a target="_blank" href="https://www.metacritic.com/about-metascores">learn more</a>
            <?php
          }
          else {
            echo $metascore_error_message;
          }
          ?>
      </div>

      <!-- Metascore input box -->
      <div class="metascore-box">
        <a target="_blank" href="https://www.metacritic.com/about-metacritic"><img style="height: 39px; width: auto;"  title="Metacritic Logo" src="images/metacritic-4.png"></a>
        <input style="vertical-align: top; width: 120px;" type="text" class="<?php echo $metascore_field; ?>" value="<?php echo $metascore; ?>" name="metascore" placeholder="Metascore" />
        <span style="font-size: 25px; vertical-align: super;">&nbsp;/100</span>
      </div>

      <!-- Gross input error message -->
      <div style="margin-left: 65px;" class="error-9 <?php echo $gross_error; ?>">
          <?php echo $gross_error_message; ?>
      </div>

      <!-- gross input box -->
      <div class="gross-box">
        <img style="margin-left: 20px; height: 39px; width: auto;" title="Gross" src="images/gross.png">
        <input type="text" style="position: absolute;width: 25px;/* text-align: left; *//* margin-right: 10px; */background: inherit;border: none;margin-left: 3px;padding: 10px 0px 0px 10px;" name="$-symbol" value="$" disabled="">
        <input type="text" style="padding-left: 19px; width: 170px;" class="align-input-box <?php echo $gross_field; ?>" value="<?php echo $gross; ?>" name="gross" placeholder="Gross (in millions)" />
        <input type="text" style="position: absolute;width: 35px;text-align: right;margin-left: 145px;background: inherit;border: none;padding: 10px 10px 0px 0px;" name="M-symbol" value="M" disabled="">
      </div>

      <!-- nominations input error message -->
      <div style="margin-left: 65px;" class="error-10 <?php echo $nominations_error; ?>">
          <?php echo $nominations_error_message; ?>
      </div>

      <!-- nominations input box -->
      <div class="nominations-box">
        <img style="margin-left: 20px; height: 39px; width: auto;" title="Nominations" src="images/nominations.png">
        <input type="text" class="align-input-box <?php echo $nominations_field; ?>" value="<?php echo $nominations; ?>" name="nominations" placeholder="Nominations" />
      </div>

      <!-- awards input error message -->
      <div style="margin-left: 65px;" class="error-11 <?php echo $awards_error; ?>">
          <?php echo $awards_error_message; ?>
      </div>

      <!-- awards input box -->
      <div class="awards-box">
        <img style="margin-left: 20px; height: 39px; width: auto;" title="Awards" src="images/awards.png">
        <input type="text" class="align-input-box <?php echo $awards_field; ?>" value="<?php echo $awards; ?>" name="awards" placeholder="Awards" />
      </div>

      <!-- Duration input error message -->
      <div style="margin-left: 65px;" class="error-12 <?php echo $duration_error; ?>">
          <?php echo $duration_error_message; ?>
      </div>

      <!-- duration input -->
      <div class="duration-box">
        <img style="margin-left: 17px; height: 39px; width: auto;" title="Duration" src="images/duration.png">
        <input type="text" class="align-input-box <?php echo $duration_field; ?>" value="<?php echo $duration; ?>" name="duration" placeholder="Duration (mins)" />
      </div>


      <!-- synopsis text area  error message -->
      <div style="margin-left: 45px;" class="error-synopsis <?php echo $synopsis_error; ?>">
          This field can't be blank
      </div>

      <div class="synopsis-box">
        <img style="width: 39px; height: auto; vertical-align: top;" src="images/synopsis-1.png" title="Synopsis">
        <!-- Synopsis text area -->
        <textarea style="display:inline;" class="<?php echo $synopsis_field; ?>" name="synopsis" rows="8" placeholder="Synopsis..."><?php echo $synopsis; ?></textarea>
      </div>

    </div>




    <!-- Submit button -->
    <p>
        <input type="submit" value="Submit" />
    </p>

</form>

<!-- script for autocomplete -->
<script>

<?php include 'autocomplete.php'; ?>

// Arrays containing lists
var all_genres =<?php print("$all_genres"); ?>;
autocomplete(document.getElementById("genre1"), all_genres);
autocomplete(document.getElementById("genre2"), all_genres);
autocomplete(document.getElementById("genre3"), all_genres);

</script>

<?php

if ($director_1_ID == $director_2_ID) {
  ?>
  <script>

  var all_countries = <?php print("$all_countries"); ?>;
  autocomplete(document.getElementById("country1"), all_countries);
  autocomplete(document.getElementById("country2"), all_countries);

  </script>
  <?php
}
elseif ($director_1_ID == "unknown" || $director_2_ID == "unknown") {
  ?>
  <script>

  var all_countries = <?php print("$all_countries"); ?>;
  autocomplete(document.getElementById("country"), all_countries);

  </script>
  <?php
}


/*
?>

<!-- TEMPLATE error message -->
<div class="<?php echo $template_error; ?>">
    This field can't be blank
</div>

<!-- TEMPLATE -->
<div class="input-text-box"> <!-- class not added in CSS yet -->
    <input type="text" class="<?php echo $template_field; ?>" value="<?php echo $template; ?>" name="template" placeholder="Template" />
</div>

<?php
*/
?>
