<?
  	include 'base.php';
    RedirectSeMancaCookie();

    $ok=true;
    $idAttivita=$_REQUEST["idAttivita"];
    $idaccertamento=$_REQUEST["idAccertamento"];
    $conn = ConnettiAlDB();
    

      if(!empty($idAttivita)) {
        // controlli per update
        $stmt = $conn->prepare("DELETE FROM Attivita WHERE idAttivita=?");
        $stmt->bind_param("i", $idAttivita);
        $ok=$stmt->execute();
        $errore=$stmt->error;
        if(!$ok) echo $errore;
        header('Location: accertamento.php?idAccertamento='.$idaccertamento);
        
      }
    $conn->close();
?>
