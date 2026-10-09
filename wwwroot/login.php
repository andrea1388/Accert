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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista soggetti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
    <h1 class="mt-4">Login</h1>
    <? if(!empty($_REQUEST["utente"])) echo "<h2><div class='alert alert-warning' role='alert'>Riconoscimento non avvenuto</div></h2>"; ?>
      <form action='login.php' method='post'>
        <div class="form-group mb-3">
          <label for="utente" class="form-label">Nome utente</label>
          <input type="text" class="form-control" id="utente" placeholder="Nome utente" name="utente" required autofocus>
        </div>
        <div class="form-group mb-3">
          <label for="password" class="form-label">password</label>
          <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
