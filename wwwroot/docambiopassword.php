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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambio password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1>Soggetto</h1>
	<? if($ok) echo "Cambio password effettuato con successo"; else echo "Errore: " . $errore; ?>
	<button type="button" class="btn btn-primary" onclick="window.location='index.php'">Home</button>    
	
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
