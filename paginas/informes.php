<?php

session_start();

//if the user is not logged
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

require_once 'header.html';
require_once 'head_side.html';
?>

<!-- Begin Page Content -->

<?php require_once 'footer.html'; ?>