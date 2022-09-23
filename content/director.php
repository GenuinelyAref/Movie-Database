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

$movies_by_director = mysqli_num_rows($find_query);

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

<?php
if ($movies_by_director == 0) {
  $margin_bottom_css = "margin-bottom: 20px;";
}
else {
  $margin_bottom_css = "";
}
?>
<!-- about director div-->
<div style="<?php echo $margin_bottom_css; ?>" class="about">
  <!-- Director name for 'about director' -->
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

  // admin only directors edit/delete area
  if (isset($_SESSION['admin'])) {
    ?>
    <div class="edit-tools-director">

        <a href="index.php?page=../admin/editdirector&directorID=<?php echo $director_to_find;?>" title="Edit director"><i class="fa fa-edit fa-2x"></i></a>

        <!-- 2 non breaking spaces -->
        &nbsp; &nbsp;

        <a href="index.php?page=../admin/deletedirector_confirm&directorID=<?php echo $director_to_find; ?>" title="Delete director"><i class="fa fa-trash fa-2x"></i></a>

    </div> <!-- end of directors edit/delete area -->
    <?php
  }
  ?>

</div> <!-- end of about director div-->

<?php


if ($movies_by_director > 0) {
  ?>
  <!-- line break-->
  </br>

  <?php

  // loop through the results and display them...
  do {

      $movie = preg_replace('/[^A-Za-z0-9.,?\s\'\-]/', ' ', $find_rs['Movie']);
      ?>

      <!-- results div-->
      <div class="results">

        <!-- movie title and certficate div -->
        <div class="movie_title">
            <!-- movie name -->
            <strong><?php echo $movie; ?></strong>

            <!-- non-breaking space -->
            &nbsp;-&nbsp;

            <!-- movie certficate -->
            <?php include 'show_certificate.php'; ?>
        </div> <!-- end of movie title and certficate div -->

        <!-- genres div -->
        <div class="genre-content">
          <!-- genre tags go here -->
          <?php include 'show_genres.php'; ?>
        </div>
        <!-- end of genres div -->

        <!-- horizontal content display -->
        <div class="all-content-parts">

          <!-- part one contents -->
          <div class="part-one-contents">
            <!-- release date -->
            <?php include 'show_release_date.php'; ?>

            <!-- line break -->
            <br />

            <!-- director details -->
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
                ?>
                <img src="images/clapperboard.png" class="content_icon" title="Director(s)">
                <?php
                echo "<strong>Co-directed</strong> with ";
                ?>
                <a href="index.php?page=director&directorID=<?php echo $co_director; ?>">
                  <?php echo $co_director_full_name; ?>
                </a>
                <?php
              }
              ?>

            <!-- line break -->
            <br />

            <img src="images/duration.png" class="content_icon" title="Duration">

            <!-- Duration -->
            <?php
            $minutes = $find_rs['Duration'];
            $hours = floor($minutes / 60);
            $remaining_minutes = $minutes % 60;
            // if duration is only in hours eg exactly 2hr
            if ($remaining_minutes == 0) {
              echo $hours."h";
            }
            // if duration is less than an hour eg 48mins
            elseif ($hours == 0) {
              echo $remaining_minutes."m";
            }
            // if duration has both hour and minutes values eg 1hr 29mins
            else {
              echo $hours."h ".$remaining_minutes."m";
            }
            ?>
          </div> <!-- end of part one contents -->

          <!-- part two contents -->
          <div class="part-two-contents">
            <!-- Gross -->
            <strong>Gross: </strong>
            <?php
            $gross = round(($find_rs['Gross']/10000000), 1)*10;
            echo "$".$gross."M";
            ?>

            <!-- line break -->
            <br />

            <?php include 'show_awards_nominations.php'; ?>
          </div> <!-- end of part two contents -->

          <!-- part three contents -->
          <div class="part-three-contents">
            <!-- metascore logo -->
            <img class="metascritic-logo-contents" src="images/metacritic-4.png" title="Metascore">
            &nbsp;
            <!-- metascore -->
            <?php include 'show_metascore.php'; ?>
          </div> <!-- end of part three contents -->
        </div> <!-- end of horizontal content display -->

        <div class="content-grid">
          <!-- Synopsis div -->
          <div class="synopsis-div">
            <!-- Synopsis -->
            <strong>Synopsis</strong>
            <p>
              <?php echo $find_rs['Synopsis']; ?>
            </p>
          </div> <!-- end of synopsis div -->

          <!-- Edit/delete movie Admin area -->
          <?php
          // if logged in, show edit / delete options
          if (isset($_SESSION['admin'])) {
            ?>
            <div class="edit-tools">

                <!-- edit movie -->
                <a href="index.php?page=../admin/editmovie&ID=<?php echo $find_rs['ID']; ?>" title="Edit movie"><i class="fa fa-edit fa-2x"></i></a>

                <!-- 2 non breaking spaces -->
                &nbsp; &nbsp;

                <!-- delete movie -->
                <a href="index.php?page=../admin/deletemovie_confirm&ID=<?php echo $find_rs['ID']; ?>" title="Delete movie"><i class="fa fa-trash fa-2x"></i></a>

            </div> <!-- / edit tools div -->
            <?php
            }
            ?>
          <!-- end of Edit/delete movie Admin area -->
        </div>

      </div> <!-- end of results div -->

    <br />

    <?php
  } // end of display results 'do' loop

  while ($find_rs = mysqli_fetch_assoc($find_query));

}
else {
  // code...
}
?>
