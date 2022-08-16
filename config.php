<?php
  $dbserver = "";
  $dbuser = "";
  $dbpass = "";
  $dbname = "";

  $conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
  $conn->set_charset('utf8mb4');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  /**
   * Generate a random UUID, using the same algorithm as the Linux command line tool uuidgen.
   * 
   * @return A random UUID.
   */
  function genUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        rand(0, 0xffff),
        rand(0, 0xffff),

        rand(0, 0xffff),

        rand(0, 0x0fff) | 0x4000,

        rand(0, 0x3fff) | 0x8000,

        rand(0, 0xffff),
        rand(0, 0xffff),
        rand(0, 0xffff)
    );
  }

?> 