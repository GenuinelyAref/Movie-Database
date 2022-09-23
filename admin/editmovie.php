<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $ID = $_REQUEST['ID'];

  // Get director ID
  $find_sql =
  "SELECT * FROM `movie`
  JOIN `director_movie` ON (`director_movie`.`DirectorID` = `movie`.`DirectorID_1`)
  WHERE `movie`.`ID` = $ID
  ";
  $find_query = mysqli_query($dbconnect, $find_sql);
  $find_rs = mysqli_fetch_assoc($find_query);

  $director_ID = $find_rs['DirectorID'];
  $first = $find_rs['First'];
  $middle = $find_rs['Middle'];
  $last = $find_rs['Last'];

  $current_director = $last.", ".$first." ".$middle;

  // Get genre list from database
  $all_genres_sql = "SELECT * FROM `genre_movie` ORDER BY `Genre` ASC";
  $all_genres = autocomplete_list($dbconnect, $all_genres_sql, 'Genre');

  // retrieve data to populate the form
  $director_2_ID = $find_rs['DirectorID_2'];
  $movie = $find_rs['Movie'];
  $release_date = $find_rs['Release Date'];
  $certificate = $find_rs['CertificateID'];
  $duration = $find_rs['Duration'];
  $metascore = $find_rs['Metascore'];
  $gross = $find_rs['Gross'];
  $gross = $gross / 1000000;
  $synopsis = $find_rs['Synopsis'];
  $awards = $find_rs['Awards'];
  $nominations = $find_rs['Nominations'];

  // get genre ids
  $genre1_ID = $find_rs['GenreID_1'];
  $genre2_ID = $find_rs['GenreID_2'];
  $genre3_ID = $find_rs['GenreID_3'];

  // retrieve genre names from genre table
  $genre_1_rs = get_rs($dbconnect, "SELECT * FROM `genre_movie` WHERE `GenreID` = $genre1_ID");
  $genre_2_rs = get_rs($dbconnect, "SELECT * FROM `genre_movie` WHERE `GenreID` = $genre2_ID");
  $genre_3_rs = get_rs($dbconnect, "SELECT * FROM `genre_movie` WHERE `GenreID` = $genre3_ID");

  $genre_1 = $genre_1_rs['Genre'];
  if (isset($genre_2_rs['Genre'])) {
    $genre_2 = $genre_2_rs['Genre'];
  }
  else {
    $genre_2 = "";
  }
  if (isset($genre_3_rs['Genre'])) {
    $genre_3 = $genre_3_rs['Genre'];
  }
  else {
    $genre_3 = "";
  }



  $has_errors = "no";

  // set up error fields / visibility
  $director_selection_error = $movie_error = $release_date_error = $certificate_error = $genre_1_error = $duration_error = $metascore_error = $gross_error = $awards_error = $nominations_error = $synopsis_error =  "no-error";
  $director_selection_field = $movie_field = $release_date_field = $certificate_field = $genre_1_field = $duration_field = $metascore_field = $gross_field = $awards_field = $nominations_field = $synopsis_field = "form-ok";

  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get data from form
    $director_1_ID = mysqli_real_escape_string($dbconnect, $_POST['co-director-one']);
    $director_2_ID = mysqli_real_escape_string($dbconnect, $_POST['co-director-two']);
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

    // check that directors are not the same
    if ($director_1_ID == $director_2_ID) {
      $has_errors = "yes";
      $director_selection_error = "error-text";
      $director_selection_field = "form-error";
    }

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

      // edit database entry
      $editentry_sql = "UPDATE `movie` SET `Release Date` = '$release_date', `Movie` = '$movie', `DirectorID_1` = '$director_1_ID', `DirectorID_2` = '$director_2_ID', `CertificateID` = '$certificate', `GenreID_1` = '$genreID_1', `GenreID_2` = '$genreID_2',
       `GenreID_3` = '$genreID_3', `Metascore` = '$metascore', `Gross` = '$gross', `Awards` = '$awards', `Nominations` = '$nominations', `Duration` = '$duration', `Synopsis` = '$synopsis'
       WHERE `movie`.`ID` = '$ID'
      ";

      $editentry_query = mysqli_query($dbconnect, $editentry_sql);


      // get movie ID for next page
      $get_movie_sql = "SELECT * FROM `movie` WHERE `ID` = '$ID'";
      $get_movie_query = mysqli_query($dbconnect, $get_movie_sql);
      $get_movie_rs = mysqli_fetch_assoc($get_movie_query);

      $movie_ID = $get_movie_rs['ID'];
      $_SESSION['Edit_Movie_Success']=$movie_ID;

      // Go to success page
      header('Location: index.php?page=editmovie_success');

    } // end add-entry to database if statement



  } // end submit form if statement


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Edit Movie</h1>
<p>
    Change the details for your movie.
</p>

<form autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/editmovie&ID=$ID");?>">

    <!-- note to direct user to 'add author' page -->
    <p>
      <i>If you need to change this movie's director and the director you need is NOT
      in the lists below, please <a href="index.php?page=../admin/add_director_part_one" target="_blank">add the director</a> first then come back and reload this page to refresh the list.</i>
    </p>
    <!--


    start of copy/paste

    -->


    <!-- two directors -->
    <h3>Change directors:</h3>

    <!-- director error message -->
    <div class="<?php echo $director_selection_error; ?>">
        You can't pick the same director twice.
        <br>
        Please try again
        <br><br>
    </div>

    <!-- co-director 1 -->
    <div>
      <strong>Co-director 1:</strong> &nbsp;

      <!-- dropdown menu -->
      <select name="co-director-one" class="<?php echo $director_selection_field; ?>">
          <?php

          // get directors from database
          $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
          $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
          $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

          do {
            if ($all_directors_rs['DirectorID'] == $director_ID) {
              ?>
              <option selected value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            }
            else {
              ?>
              <option value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            }
          } // end of director options 'do'
          while ($all_directors_rs=mysqli_fetch_assoc($all_directors_query))
           ?>
      </select>
    </div>

    </br>
    <!-- co-director 2 -->
    <div>
      <strong>Co-director 2:</strong> &nbsp;
      <!-- dropdown menu -->
      <select name="co-director-two" class="<?php echo $director_selection_field; ?>">
          <!-- existing director -->
          <?php

          // get directors from database
          $all_directors_sql = "SELECT * FROM `director_movie` ORDER BY `Last` ASC";
          $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
          $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

          // if 2nd director is set
          if ($director_2_ID == 0) {
            ?>
            <option selected value="0">No co-director</option>
            <?php
          }
          else {
            ?>
            <option value="0">No co-director</option>
            <?php
          }

          do {
            if ($all_directors_rs['DirectorID'] == $director_2_ID) {
              ?>
              <option selected value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            }
            else {
              ?>
              <option value="<?php echo $all_directors_rs['DirectorID'];?>"><?php echo $all_directors_rs['Last'];?>, <?php echo $all_directors_rs['First'];?> <?php echo $all_directors_rs['Middle'];?></option>
              <?php
            }
          } // end of director options 'do'
          while ($all_directors_rs=mysqli_fetch_assoc($all_directors_query))
           ?>
      </select>
    </div>

    </br>

    <!--


    end of copy/paste

    -->

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
