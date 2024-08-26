<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the ESTELARSYSTEM table exists. */
  VerifyEsTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the ESTELARSYSTEM table. */
  $CelestialBody_name = htmlentities($_POST['NAME']);
  $CelestialBody_type = htmlentities($_POST['TYPE']);
  $CelestialBody_declination = htmlentities($_POST['DECLINATION']);
  $CelestialBody_ar = htmlentities($_POST['AR']);
  $CelestialBody_habitable = htmlentities($_POST['HABITABLE']);

  if (strlen($CelestialBody_name) || strlen($CelestialBody_type)) {
    CelestialBody($connection, $CelestialBody_name, $CelestialBody_type, $CelestialBody_declination, $CelestialBody_ar, $CelestialBody_habitable);
  }
?>

<!-- Input form -->
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
  <table border="0">
    <tr>
      <td>NAME</td>
      <td>TYPE</td>
      <td>DECLINATION</td>
      <td>AR</td>
      <td>HABITABLE</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="NAME" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="TYPE" maxlength="90" size="60" />
      </td>
      <td>
        <input type="number" name="DECLINATION" maxlength="90" size="60" />
      </td>
      <td>
        <input type="number" name="AR" maxlength="90" size="60" />
      </td>
      <td>
        <input type="radio" name="HABITABLE" value="1" /> Yes 
        <input type="radio" name="HABITABLE" value="0" /> No
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>NAME</td>
    <td>TYPE</td>
    <td>DECLINATION</td>
    <td>ASCENÇÃO RETA</td>
    <td>HABITABLE</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM ESTELARSYSTEM");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>", $query_data[0], "</td>",
       "<td>", $query_data[1], "</td>",
       "<td>", $query_data[2], "</td>",
       "<td>", $query_data[3], "</td>",
       "<td>", $query_data[4], "</td>",
       "<td>", ($query_data[5] ? 'Yes' : 'No'), "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function CelestialBody($connection, $name, $type, $declination, $ascenr, $habitable) {
   $n = mysqli_real_escape_string($connection, $name);
   $t = mysqli_real_escape_string($connection, $type);
   $d = $declination;
   $ar = $ascenr;
   $h =$habitable;

   $query = "INSERT INTO ESTELARSYSTEM (NAME, TYPE, DECLINATION, AR, HABITABLE) VALUES ('$n', '$t', $d, $ar, $h);";

   if (!mysqli_query($connection, $query)) echo "<p>Error adding employee data.</p>";
}

/* Check whether the table exists and, if not, create it. */
function VerifyEsTable($connection, $dbName) {
  if (!TableExists("ESTELARSYSTEM", $connection, $dbName)) {
     $query = "CREATE TABLE ESTELARSYSTEM (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         TYPE VARCHAR(90),
         DECLINATION FLOAT,
         AR FLOAT,
         HABITABLE BOOLEAN
       )";

     if (!mysqli_query($connection, $query)) echo "<p>Error creating table.</p>";
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if (mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
