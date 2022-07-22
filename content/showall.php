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

            <!-- director details -->
            <b>Directed by:</b>
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
          <b>Metascore:</b>
          <u><?php echo $find_rs['Metascore']; ?></u> / 100

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
