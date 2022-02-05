<h2>All Results</h2>

<?php

$find_sql = "SELECT * FROM `movie`
JOIN `director_movie` ON (`director_movie`.`DirectorID` = `movie`.`DirectorID_1`)
";
$find_query = mysqli_query($dbconnect, $find_sql);
$find_rs = mysqli_fetch_assoc($find_query);


// loop through the results and display them...
do {

    $movie = preg_replace('/[^A-Za-z0-9.,?\s\'\-]/', ' ', $find_rs['Movie']);

    $first = $find_rs['First'];
    $middle = $find_rs['Middle'];
    $last = $find_rs['Last'];

    // director name...
    $full_name = $first." ".$middle." ".$last;

    ?>

    <div class="results">
        <p>
            <?php echo $movie; ?>

            <!-- line break -->
            <br />

            <!-- only one director is associated with the movie -->
            Directed by:
            <a href="index.php?page=director&directorID=<?php echo $find_rs['DirectorID_1']; ?>">
              <?php echo $full_name; ?>
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

        </p>

      <!-- subject tags go here -->
      <?php include("show_subjects.php"); ?>

    </div>

  <br />

  <?php
} // end of display results 'do' loop

while ($find_rs = mysqli_fetch_assoc($find_query));

?>