<?
  	include 'base.php';
    RedirectSeMancaCookie();

    $ok=true;
    $idsoggetto=$_REQUEST["idSoggetto"];
    $idaccertamento=$_REQUEST["idAccertamento"];
    $ruolo=$_REQUEST["ruolo"];
    $descrizioneruolo=$_REQUEST["descrizioneruolo"];
    $conn = ConnettiAlDB();
    echo $idsoggetto." ".$idaccertamento." ".$ruolo." ".$descrizioneruolo."<br>";    

    if(!empty($idaccertamento)) {
      // controlli per update
      $stmt = $conn->prepare("INSERT INTO SoggettoAccertamento (idSoggetto,idAccertamento,ruolo,descRuolo) VALUES (?,?,?,?)");
      $stmt->bind_param("iiis", $idsoggetto,$idaccertamento,$ruolo,$descrizioneruolo);
      $ok=$stmt->execute();
      $errore=$stmt->error;
      if(!$ok) echo $errore;
      header("Location: accertamento.php?idAccertamento=".$idaccertamento);
    }
    $conn->close();
?>
