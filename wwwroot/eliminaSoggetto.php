<?
  	include 'base.php';
    RedirectSeMancaCookie();
    $ok=true;
    $errore="";
    $id=$_REQUEST["idSoggetto"];
    $conn = ConnettiAlDB();
    if(empty($id)) die("manca id");
    $stmt = $conn->prepare("select count(*) from SoggettoAccertamento where idSoggetto=?");
    $stmt->bind_param("i", $id);
    if(!$stmt->execute()) die($stmt->error);
    $result = $stmt->get_result();
    $row = mysqli_fetch_row($result);
    if($row[0]>0)
    {
      $errore="Soggetto presente in accertamenti";
      $ok=false;
    } 
    else{
      $stmt = $conn->prepare("DELETE FROM Soggetto WHERE idSoggetto=?");
      $stmt->bind_param("i", $id);
      if(!$stmt->execute()) {
        $errore=$stmt->error;
        $ok=false;
      }
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accertamento</title>
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
	<? if($ok) echo "Soggetto eliminato id=". $id; else echo "Errore: Soggetto non eliminato: " . $errore; ?>
	<button type="button" class="btn btn-primary" onclick="window.location='index.php'">Home</button>    
	
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
