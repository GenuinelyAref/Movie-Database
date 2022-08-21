<!-- genre tags go here -->
<p>
  <?php
  $genre1_ID = $find_rs['GenreID_1'];
  $genre2_ID = $find_rs['GenreID_2'];
  $genre3_ID = $find_rs['GenreID_3'];

  $all_genres = array($genre1_ID, $genre2_ID, $genre3_ID);

  // loop through genre ID's and look up the genre name
  foreach ($all_genres as $genre) {
    // get genre name
    $genre_sql = "SELECT * FROM `genre_movie` WHERE `GenreID` = $genre";
    $genre_query = mysqli_query($dbconnect, $genre_sql);
    $genre_rs = mysqli_fetch_assoc($genre_query);

    if ($genre != 0) {
      ?>
      <!-- link genre to its genre page -->
      <span class="tag">
        <a class="genre-tag" href="index.php?page=genre&genreID=<?php echo $genre_rs['GenreID']; ?>">
          <?php echo $genre_rs['Genre']; ?>
        </a>
      </span>&nbsp;
    <?php

  } // end of if statement

  } // end subject loop
  ?>

</p> <!-- end of genre <p> tag -->
