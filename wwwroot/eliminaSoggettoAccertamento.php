<?
  	include 'base.php';
    RedirectSeMancaCookie();

    $ok=true;
    $idSoggettoAccertamento=$_REQUEST["idSoggettoAccertamento"];
    $idaccertamento=$_REQUEST["idAccertamento"];
    $conn = ConnettiAlDB();
    

      if(!empty($idSoggettoAccertamento)) {
        // controlli per update
        $stmt = $conn->prepare("DELETE FROM SoggettoAccertamento WHERE idSoggettoAccertamento=?");
        $stmt->bind_param("i", $idSoggettoAccertamento);
        $ok=$stmt->execute();
        $errore=$stmt->error;
        if(!$ok) echo $errore;
        header('Location: accertamento.php?idAccertamento='.$idaccertamento);
        
      }
    $conn->close();
?>
