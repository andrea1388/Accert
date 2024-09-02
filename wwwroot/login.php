<?
	include 'base.php';
  $ok=false;
	if(!empty($_REQUEST["utente"])) {
    $conn = ConnettiAlDB();
    $utente=isset($_REQUEST["utente"])? EscapeIfNotEMptyOrNull($conn,$_REQUEST["utente"]) : NULL;
    $password=isset($_REQUEST["password"])? EscapeIfNotEMptyOrNull($conn,$_REQUEST["password"]) : NULL;
    $stmt = $conn->prepare("SELECT * FROM Soggetto where login=?");
    $stmt->bind_param("s", $utente);
    $stmt->execute();
    $result = $stmt->get_result();
    $logatt="Login:";
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //echo $row['pwdhash'];
        //echo password_hash($password, PASSWORD_BCRYPT);
        if(password_verify($password,$row['pwdhash'])) 
        //if($row['password']==$password) 
        {
          session_name("accertV2");
          session_start();
          $_SESSION['idutente'] = $row['idSoggetto'];
          $_SESSION['permessi'] = $row['permessi']; 
          header('Location: index.php'); 
          $logatt=$logatt." OK";
          $ok=true;
        }
        else {
          $logatt=$logatt." FAIL (bad pass)";
        }
    }
    else {
      $logatt=$logatt." FAIL (sconosciuto)";
    }
    $logatt=$logatt. " user: ".$utente;
    $logatt=substr($logatt, 0,99);
    $stmt = $conn->prepare("INSERT INTO Log (operazione) VALUES (?)");
    $stmt->bind_param("s", $logatt);
    if(!$stmt->execute()) die ($logatt. " ". $stmt->error);
    $conn->close();

  }
  if($ok) die();
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista soggetti</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <h1>Login</h1>
    <? if(!empty($_REQUEST["utente"])) echo "<h2><div class='alert alert-warning' role='alert'>Riconoscimento non avvenuto</div></h2>"; ?>
      <form action='login.php' method='post'>
        <div class="form-group">
          <label for="utente">Nome utente</label>
          <input type="text" class="form-control" id="utente" placeholder="Nome utente" name="utente" required autofocus>
        </div>
        <div class="form-group">
          <label for="Password">password</label>
          <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-default">Login</button>
      </form>
</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
