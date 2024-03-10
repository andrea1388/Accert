<?
  	include 'base.php';
    RedirectSeMancaCookie();
    $ok=true;
    $idDocumento=$_REQUEST["idDocumento"];
    $idAccertamento=$_REQUEST["idAccertamento"];
    if(empty($idDocumento)) die("manca iddoc");
    $conn = ConnettiAlDB();
    $stmt = $conn->prepare("DELETE FROM Documento WHERE idDocumento=?");
    $stmt->bind_param("i", $idDocumento);
    $ok=$stmt->execute();
    $errore=$stmt->error;
    if(!$ok) die($errore);
    header('Location: accertamento.php?idAccertamento='.$idAccertamento);
    $conn->close();
?>
