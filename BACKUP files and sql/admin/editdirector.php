<?php

// check user is logged in
if (isset($_SESSION['admin'])) {

  $director_ID = $_REQUEST['directorID'];

  $all_countries_sql = "SELECT * FROM `country_movie` ORDER BY `Country` ASC";
  $all_countries = autocomplete_list($dbconnect, $all_countries_sql, 'Country');

  // get director details from database
  $all_directors_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = $director_ID";
  $all_directors_query = mysqli_query($dbconnect, $all_directors_sql);
  $all_directors_rs = mysqli_fetch_assoc($all_directors_query);

  // retrieve director data from DB
  $url = $all_directors_rs['URL'];
  $first = $all_directors_rs['First'];
  $middle = $all_directors_rs['Middle'];
  $last = $all_directors_rs['Last'];
  $yob = $all_directors_rs['YOB'];
  $country_ID = $all_directors_rs['CountryID'];

  // Get country from country table
  $country_rs = get_rs($dbconnect, "SELECT * FROM `country_movie` WHERE `Country_ID` = '$country_ID'");
  $country = $country_rs['Country'];

  // set up error fields/messages
  $url_error = $last_error = $first_error = $yob_error = $country_error = "no-error";
  $url_field = $last_field = $first_field = $yob_field = $country_field = "form-ok";

  $has_errors = "no";


  // Code below executes when the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // director details
    $url = mysqli_real_escape_string($dbconnect, $_POST['url']);
    $first = mysqli_real_escape_string($dbconnect, $_POST['first']);
    $middle = mysqli_real_escape_string($dbconnect, $_POST['middle']);
    $last = mysqli_real_escape_string($dbconnect, $_POST['last']);
    $yob = mysqli_real_escape_string($dbconnect, $_POST['yob']);
    $country = mysqli_real_escape_string($dbconnect, $_POST['country']);


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

    if ($has_errors != "yes") {

      // get country ID using get_ID function
      $countryID = get_ID($dbconnect, 'country_movie', 'Country_ID', 'Country', $country);

      // edit director SQL
      $edit_director_sql = "UPDATE `director_movie` SET `First` = '$first', `Middle` = '$middle', `Last` = '$last', `URL` = '$url', `YOB` = '$yob', `CountryID` = '$countryID'
      WHERE `director_movie`.`DirectorID` = '$director_ID'";
      // edit director details from DB
      $edit_director_query = mysqli_query($dbconnect, $edit_director_sql);


      // Go to success page
      header('Location: index.php?page=director&directorID='.$director_ID);

    } // end add-entry to database if statement



  } // end submit form if statement


} // end user logged in if

else {
  $login_error = 'Please login to access this page';
  header('Location: index.php?page=../admin/login&error='.$login_error);
} // end user not logged in else

?>

<h1>Edit director</h1>
<p>
    Change the details for your director.
</p>

<form autocomplete="off" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?page=../admin/editdirector&directorID=$director_ID");?>">

    <!-- take details of new director -->
    <h2>Change director details:</h2>

    </br>

    <!-- First name error message -->
    <div class="<?php echo $first_error; ?>">
        This field can't be blank
    </div>

    <!-- First name -->
    <div>
        <input type="text" class="new-director-mid-width new-director-spacing <?php echo $first_field; ?>" value="<?php echo $first; ?>" name="first" placeholder="First name" />
    </div>

    <!-- Middle name -->
    <div>
        <input type="text" class="new-director-mid-width new-director-spacing" value="<?php echo $middle; ?>" name="middle" placeholder="Middle name" />
    </div>

    <!-- Last name error message -->
    <div class="<?php echo $last_error; ?>">
        This field can't be blank
    </div>

    <!-- Last name -->
    <div>
        <input type="text" class="new-director-mid-width new-director-spacing <?php echo $last_field; ?>" value="<?php echo $last; ?>" name="last" placeholder="Last name" />
    </div>

    <!-- URL error message -->
    <div class="<?php echo $url_error; ?>">
        Enter a valid URL
    </div>

    <!-- URL -->
    <div>
        <input type="text" class="new-director-long-width new-director-spacing <?php echo $url_field; ?>" value="<?php echo $url; ?>" name="url" placeholder="URL" />
    </div>

    <!-- YOB error message -->
    <div class="<?php echo $yob_error; ?>">
        Enter a valid year
    </div>

    <!-- YOB -->
    <div>
        <input type="text" maxlength="4" class="new-director-small-width new-director-spacing <?php echo $yob_field; ?>" value="<?php echo $yob; ?>" name="yob" placeholder="Born (year)" />
    </div>

    <!-- Country error message -->
    <div class="<?php echo $country_error; ?>">
        This field can't be blank
    </div>

    <!-- Country -->
    <div class="autocomplete">
        <input type="text" id="country" class="new-director-mid-width new-director-spacing <?php echo $country_field; ?>" value="<?php echo $country; ?>" name="country" placeholder="Country" />
    </div>

    <!-- Submit button -->
    <p>
        <input type="submit" value="Submit" />
    </p>

</form>

<!-- script for autocomplete -->
<script>
<?php include 'autocomplete.php'; ?>

var all_countries = <?php print("$all_countries"); ?>;
autocomplete(document.getElementById("country"), all_countries);

</script>
