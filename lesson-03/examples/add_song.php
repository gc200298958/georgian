<?php

  // assign $_POST values to variables
  $artist_id = $_POST['artist'];
  $title = $_POST['title'];
  $length = $_POST['length'];

  // get our connection script
  if ( preg_match('/Heroku|georgian\.shaunmckinnon\.ca/i', $_SERVER['HTTP_HOST']) ) {
    // remote server
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $host = $url["host"];
    $dbname = substr($url["path"], 1);
    $username = $url["user"];
    $password = $url["pass"];
  } else { // localhost
    $host = 'localhost';
    $dbname = 'comp-1006-lesson-examples';
    $username = 'root';
    $password = 'root';
  }

  $dbh = new PDO( "mysql:host={$host};dbname={$dbname}", $username, $password );
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

  // build the SQL statment with placeholders
  $sql = 'INSERT INTO songs (artist_id, title, length) VALUES (:artist_id, :title, :length)';

  // assign our values to variables
  $artist_id = $_POST['artist'];
  $title = $_POST['title'];

  // convert our time to time stamp format
  $length = "{$length['hours']}:{$length['minutes']}:{$length['seconds']}";

  // prepare the SQL statment
  $sth = $dbh->prepare($sql);

  // bind the values
  $sth->bindParam(':artist_id', $artist_id);
  $sth->bindParam(':title', $title, PDO::PARAM_STR, 100);
  $sth->bindParam(':length', $length, PDO::PARAM_STR);

  // execute the SQL
  $sth->execute();

  // close the DB connection
  $dbh = null;

  // we set the 'success' session variable and store our message
  $_SESSION['success'] = "Song was added successfully";

  // we redirect to a page with a success message
  header( "Location: add_confirmed.php" );

?>