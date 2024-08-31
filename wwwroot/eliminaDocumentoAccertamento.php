<?
  	include 'base.php';
    RedirectSeMancaCookie();

    $ok=true;
    $idDocumento=$_REQUEST["idDocumento"];
    $idaccertamento=$_REQUEST["idAccertamento"];
    $conn = ConnettiAlDB();
    if(!empty($idDocumento)) {
      // controlli per update
      $stmt = $conn->prepare("DELETE FROM Documento WHERE idDocumento=?");
      $stmt->bind_param("i", $idDocumento);
      $ok=$stmt->execute();
      $errore=$stmt->error;
      if(!$ok) echo $errore;
      header('Location: accertamento.php?idAccertamento='.$idaccertamento);
      
    }
    $conn->close();
?>