<?
  	include 'base.php';
    RedirectSeMancaCookie();
    $ok=true;
    $errore="";
    $idutente=$_SESSION['idutente'];
    $pwd1=$_REQUEST['nuovapassword1'];
    $pwd2=$_REQUEST['nuovapassword2'];
    $oldpwd=$_REQUEST['vecchiapassword'];

    if($pwd1!=$pwd2) {
        $ok=false; 
        $errore="le password non coincidono";
    }
    elseif($oldpwd==$pwd2) {
        $ok=false; 
        $errore="la password coincide con quella vecchia";
    }
    elseif(preg_match('/[A-Z]/', $pwd1))
    {
        $ok=false; 
        $errore="la password deve contenere almeno una maiuscola";
    }
    elseif(preg_match('/[a-z]/', $pwd1))
    {
        $ok=false; 
        $errore="la password deve contenere almeno una minuscola";
    }
    elseif(preg_match('/[0-9]/', $pwd1))
    {
        $ok=false; 
        $errore="la password deve contenere almeno un numero";
    }
    elseif(preg_match('/[^A-Za-z0-9]/', $pwd1))
    {
        $ok=false; 
        $errore="la password deve contenere almeno un carattere speciale";
    }
    elseif(strlen($pwd1)<8) {
        $ok=false; 
        $errore="password nuova troppo corta";
    }
    else {
        $conn = ConnettiAlDB();
        if(empty($idutente)) die("manca idutente");
        /*
        $stmt = $conn->prepare("select password from Soggetto where idSoggetto=?");
        $stmt->bind_param("i", $idutente);
        if(!$stmt->execute()) die($stmt->error);
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if(!$row['password']==password_hash($oldpwd, PASSWORD_BCRYPT)) {$ok=false; $errore="Vecchia password errata";}
        }
        */
        echo $idutente;
        $pwdhash=password_hash($pwd1, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("update Soggetto set pwdhash=? where idSoggetto=?");
        $stmt->bind_param("si", $pwdhash, $idutente);
        $ok=$stmt->execute();
        $errore=$stmt->error;
        $conn->close();
    
    }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambio password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1 class="mt-3">Soggetto</h1>
	<? if($ok) echo "Cambio password effettuato con successo"; else echo "Errore: " . $errore; ?>
	<button type="button" class="btn btn-primary" onclick="window.location='index.php'">Home</button>    
	
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
