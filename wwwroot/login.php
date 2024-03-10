<?
	include 'base.php';
  GetIfSet($ufficio,"ufficio");
  GetIfSet($utente,"utente");
  GetIfSet($password,"password");
  $msg="";
  if(!empty($ufficio) && !empty($utente) && !empty($password)) 
  {
    if(VerificaUfficio($msg,$ufficio,$dbuser,$dbname))
    {
      if(VerificaUtente($msg,$utente,$password,$dbuser,$dbname,$idutente,$permessi))
      {
        session_name("accertV2");
        session_start();
        $_SESSION['dbname'] = $dbname;
        $_SESSION['dbuser'] = $dbuser;
        $_SESSION['idutente'] = $idutente;
        $_SESSION['permessi'] = $permessi; 
        header('Location: index.php'); 
        die();
      }
    }
  }
 
  function VerificaUfficio(&$msg,$ufficio,&$dbuser,&$dbname)
  {
    include('db.inc.php');
    $ok=false;
    $connDbUff = new mysqli($host, $dbuserUffici, $dbpwd, $dbnameUffici);
    $stmt = $connDbUff->prepare("SELECT * FROM Ufficio where codice=?");
    $stmt->bind_param("s", $ufficio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) 
    {
      $row = $result->fetch_assoc();
      $dbname = $row['dbName'];
      $dbuser = $row['dbUser'];
      $dataoggi = new DateTime('now');
      $datatermine =  DateTime::createFromFormat ( "Y-m-d H:i:s", $row["dataTermine"] );
      if($datatermine>=$dataoggi)
      {
        $ok=true;
      }
      else
      {
        $msg="account scaduto";
      }
      if(empty($dbname) || empty($dbuser))
      {
        $ok=false;
        $msg="dbname or dbuser not set";
      }
    }
    else
    {
      $msg="ufficio non trovato: ".$ufficio;
    }
    $connDbUff->close();
    return $ok;
  }

function VerificaUtente(&$msg,$utente,$password,$dbuser,$dbname,&$idutente,&$permessi)
{
  include('db.inc.php');
  $ok=false;
  $conn = ConnettiAlDBP($dbuser,$dbname);
  $stmt = $conn->prepare("SELECT * FROM Soggetto where login=?");
  $stmt->bind_param("s", $utente);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) 
  {
    
    $row = $result->fetch_assoc();
    if(password_verify($password,$row['pwdhash']))
    {
      $idutente=$row['idSoggetto'];
      $permessi=$row['permessi'];
      logAttivita($conn,"Login OK ".$utente);
      $ok=true;
    }
    else
    {
      logAttivita($conn,"Login Fail (bad password): ".$utente);
      $msg="password errata";
    }
  }
  else
  {
    $msg="utente inesistente";
    logAttivita($conn,"Login Fail (bad user): ".$utente);
  }
  $conn->close();
  return $ok;
}




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
    <? if(!empty($msg)) echo "<h2><div class='alert alert-warning' role='alert'>".$msg."</div></h2>"; ?>
      <form action='login.php' method='post'>
        <div class="form-group">
          <label for="ufficio">Codice ufficio</label>
          <input type="text" class="form-control" id="ufficio" placeholder="Codice ufficio" name="ufficio" required autofocus>
        </div>
        <div class="form-group">
          <label for="utente">Nome utente</label>
          <input type="text" class="form-control" id="utente" placeholder="Nome utente" name="utente" required>
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
