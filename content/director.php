<?php

if (!isset($_REQUEST['directorID'])) {
  header('Location: index.php');
}

$director_to_find = $_REQUEST['directorID'];
$co_director_full_name = "";

$find_sql =
"SELECT * FROM `movie`
JOIN `director_movie` ON (`director_movie`.`DirectorID` = `movie`.`DirectorID_1`)
WHERE `DirectorID_1` = $director_to_find
OR `DirectorID_2` = $director_to_find
";
$find_query = mysqli_query($dbconnect, $find_sql);
$find_rs = mysqli_fetch_assoc($find_query);

// the following code is to call the director's details from the director table
$directors_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = $director_to_find";
$directors_query = mysqli_query($dbconnect, $directors_sql);
$directors_rs = mysqli_fetch_assoc($directors_query);

$director_countryid = $directors_rs['CountryID'];
// get country names
$countries_sql = "SELECT * FROM `country_movie` WHERE `Country_ID` = $director_countryid";
$countries_query = mysqli_query($dbconnect, $countries_sql);
$countries_rs = mysqli_fetch_assoc($countries_query);
?>

<!-- line break-->
</br>

<!-- Director name for 'about director' -->
<div class="about">
  <h2>
    <?php echo $directors_rs['First']." ".$directors_rs['Middle']." ".$directors_rs['Last'];?>
  </h2>


  <?php
  if ($directors_rs['YOB'] != 0 AND $directors_rs['CountryID'] != 0) {
    ?>
    <p><strong>Born:</strong> <?php echo $directors_rs['YOB'].", ".$countries_rs['Country']; ?></p>
    <?php
  }
  elseif ($directors_rs['YOB'] != 0 AND $directors_rs['CountryID'] = 0) {
    ?>
    <p><strong>Born:</strong> <?php echo $directors_rs['YOB']?></p>
    <?php
  }
  elseif ($directors_rs['YOB'] = 0 AND $directors_rs['CountryID'] != 0) {
    ?>
    <p><strong>Born:</strong> <?php echo $countries_rs['Country']; ?></p>
    <?php
  }
  ?>

</div>

<!-- line break-->
</br>

<?php

// loop through the results and display them...
do {

    $movie = preg_replace('/[^A-Za-z0-9.,?\s\'\-]/', ' ', $find_rs['Movie']);

    ?>

    <div class="results">
        <div class="movie_title">
            <!-- movie name -->
            <b><?php echo $movie; ?></b>

            <!-- non-breaking space -->
            &nbsp;-&nbsp;

            <!-- movie certficate -->
            <?php include 'show_certificate.php'; ?>
        </div>
        <p>
            <?php include 'show_release_date.php'; ?>

            <!-- line break -->
            <br />

            <?php


            // if second directorID is set and not zero then show both
            if (isset($find_rs['DirectorID_2']) AND $find_rs['DirectorID_2'] != 0) {
              if ($find_rs['DirectorID_1'] == $director_to_find) {
                $main_director = $find_rs['DirectorID_1'];
                $co_director = $find_rs['DirectorID_2'];
              }
              else {
                $main_director = $find_rs['DirectorID_2'];
                $co_director = $find_rs['DirectorID_1'];
              }
              $main_director_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = $main_director";
              $main_director_query = mysqli_query($dbconnect, $main_director_sql);
              $main_director_rs = mysqli_fetch_assoc($main_director_query);
              $co_director_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = $co_director";
              $co_director_query = mysqli_query($dbconnect, $co_director_sql);
              $co_director_rs = mysqli_fetch_assoc($co_director_query);

              // director name...
              $co_director_full_name = $co_director_rs['First']." ".$co_director_rs['Middle']." ".$co_director_rs['Last'];
              $main_director_full_name = $main_director_rs['First']." ".$main_director_rs['Middle']." ".$main_director_rs['Last'];
            }
            else {
              $main_director_full_name = $find_rs['First']." ".$find_rs['Middle']." ".$find_rs['Last'];
            }


            if ($find_rs['DirectorID_2'] != 0) {
              echo "Co-directed with ";
              ?>
              <a href="index.php?page=director&directorID=<?php echo $co_director; ?>">
                <?php echo $co_director_full_name; ?>
              </a>
              <?php
            }
            ?>

            <!-- line break -->
            <br />

        </p> <!-- end of release date, director and certificate <p> tag-->

        <!-- Duration -->
        <p>
            <?php
            $minutes = $find_rs['Duration'];
            $hours = floor($minutes / 60);
            $remaining_minutes = $minutes % 60;
            echo $hours."h ".$remaining_minutes."m";
            ?>
        </p>

        <!-- Metascore -->
        <p>

          <?php include 'show_metascore.php'; ?>

          <!-- line break -->
          <br />

          <!-- Gross -->
          <b>Gross:</b>
          $<?php echo $find_rs['Gross']; ?>


          <!-- line break -->
          <br />

          <?php include 'show_awards_nominations.php'; ?>

        </p>

        <!-- genre tags go here -->
        <?php include 'show_genres.php'; ?>

        <!-- line break -->
        <br />

        <!-- Synopsis -->
        <b>Synopsis</b>
        <p>
          <?php echo $find_rs['Synopsis']; ?>
        </p>

    </div>

  <br />

  <?php
} // end of display results 'do' loop

while ($find_rs = mysqli_fetch_assoc($find_query));

?>
