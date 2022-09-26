<h2>Success!</h2>

<p>You have succesfully added the following movie into the database:</p>

<?php

$movie_ID = $_SESSION['Movie_Success'];

$find_sql = "SELECT * FROM `movie`
JOIN `director_movie` ON (`director_movie`.`DirectorID` = `movie`.`DirectorID_1`)
WHERE `ID` = '$movie_ID'
";
$find_query = mysqli_query($dbconnect, $find_sql);
$find_rs = mysqli_fetch_assoc($find_query);


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
          <img src="images/clapperboard.png" class="content_icon" title="Director(s)">
          <strong>Directed by:</strong>
          <a href="index.php?page=director&directorID=<?php echo $find_rs['DirectorID_1']; ?>">
            <?php
            // director name...
            $first = $find_rs['First'];
            $middle = $find_rs['Middle'];
            $last = $find_rs['Last'];
            $full_name = $first." ".$middle." ".$last;

            echo $full_name; ?>
          </a>

          <?php

          // if second directorID is set and not zero then show both
          if ($find_rs['DirectorID_2'] != 0) {
            $director_two = $find_rs['DirectorID_2'];
            $director_sql = "SELECT * FROM `director_movie` WHERE `DirectorID` = $director_two";
            $director_query = mysqli_query($dbconnect, $director_sql);
            $director_rs = mysqli_fetch_assoc($director_query);

            $first = $director_rs['First'];
            $middle = $director_rs['Middle'];
            $last = $director_rs['Last'];
            // director name...
            $full_name = $first." ".$middle." ".$last;
            echo " and ";
            ?>
            <a href="index.php?page=director&directorID=<?php echo $director_two; ?>">
              <?php echo $full_name; ?>
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

?>
