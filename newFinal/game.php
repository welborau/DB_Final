<?php
function showTeams()
{
  global $mysqli;
  $temp = "SELECT name FROM team";
  $rows = $mysqli->query($temp)->fetch_all();
  foreach($rows as $value){
    echo "<option value = '$value[0]'>$value[0]</option>";
  } 	
}

function showGames()
{
  global $mysqli;
  $temp = "SELECT id, home_team, away_team, home_score, away_score, date FROM game";
  $rows = $mysqli->query($temp)->fetch_all();
  echo '<form action="game.php" method="post" ><select name="game_id" required>';
  foreach($rows as $value){
    $tempHomeTeam = $mysqli->query("SELECT name FROM team WHERE id = '" . $value[1] . "'")->fetch_row()[0];
    $tempAwayTeam = $mysqli->query("SELECT name FROM team WHERE id = '" . $value[2] . "'")->fetch_row()[0];
    echo "<option value = '$value[0]'>$value[5]: $tempHomeTeam:$value[3], $tempAwayTeam:$value[4]</option>";
  } 
  echo '</select><input type="submit" value = "Submit"></input></form>';
  

/*PROBLEM IS HERE. Only want players who are playing in the current game. Find a way to uniquely identify the player when they submit info to PHP file, 
to give them a score.*/  
  if(isset($_POST['game_id']))
  {
    echo '<table border = 1> <tr> <td>First Name</td> <td>Last Name</td> <td>Team</td><td>Score</td></tr>';
    $res = $mysqli->query("SELECT p.first_name, p.last_name, t.name FROM player p INNER JOIN team t ON t.id = p.team_id")->fetch_all();
    for ($i = 0; $i < count($res); $i++){
      echo "<tr><td>" . $res[$i][0] . "</td><td>" . $res[$i][1] . "</td><td>" . $res[$i][2] . "</td><td><input type='number' name='player_id' value='0' min='0' max='99'></td></tr>";
    }
    echo "</table>";
  }	  
	
}


function deleteGame()
{
  global $mysqli;
  $temp = "SELECT id, home_team, away_team, home_score, away_score, date FROM game";
  $rows = $mysqli->query($temp)->fetch_all();
  echo '<form action="deleteGame.php" method="post" ><select name="game_id" required>';
  foreach($rows as $value){
    $tempHomeTeam = $mysqli->query("SELECT name FROM team WHERE id = '" . $value[1] . "'")->fetch_row()[0];
    $tempAwayTeam = $mysqli->query("SELECT name FROM team WHERE id = '" . $value[2] . "'")->fetch_row()[0];
    echo "<option value = '$value[0]'>$value[5]: $tempHomeTeam:$value[3], $tempAwayTeam:$value[4]</option>";
  } 
  echo '</select><input type="submit" value = "Delete" onclick = "return confirmDelete()"></input></form>';
	
}
?>

<!DOCTYPE HTML>
<html>
<head>
  <title>MLS Database</title>
  <link rel="stylesheet" href="style.css">
  <script src="functions.js"></script>
  <meta charset='UTF-8'/>
  <?php include "userinfo.php"?>
</head>
<body>
<?php include "header.php"?>

  <h2>GAMES</h2>
  <h4>Record all the details of the latest match</h4>
  
  <form action="insertGame.php" method="post" >
	<h4>ADD A GAME:</h4>
	Home Team*: <select name="home_team" required>
	  <?php showTeams(); ?>
    </select><br />
	Away Team*: <select name="away_team" required>
	  <?php showTeams(); ?>
    </select><br />
	Home Score*: <input type="number" name="home_score" value="0" min="0" max = "99" required><br />
	Away Score*: <input type="number" name="away_score" value="0" min="0" max = "99" required><br />
    </select><br />
	Date*: <input type="date" name="date" value="2015-01-01" min="2000-01-01" max="2015-12-31"><br />
	<p>* Denotes a required field.</p>
  <input type="submit">
  </form>
  
  <h4>Did your favorite player score? Log who scored, and in what games.</h4>
    <?php showGames(); ?>
  
  <h4>Some games are so ugly, you want to pretend they never happened. Now you can! Get rid of evidence that your favorite team is a perennial disappointment.</h4>
  Select Game:
    <?php deleteGame(); ?>
  
<?php include "footer.php"?>
</body>
</html>