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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accertamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1 class="mt-3">Soggetto</h1>
	<? if($ok) echo "Soggetto eliminato id=". $id; else echo "Errore: Soggetto non eliminato: " . $errore; ?>
	<button type="button" class="btn btn-primary" onclick="window.location='index.php'">Home</button>    
	
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
