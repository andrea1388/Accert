<?  
	include 'base.php';
  RedirectSeMancaCookie();
  if(empty($_REQUEST["idAccertamento"])) die("id non trovato");
  if(empty($_REQUEST["filename"])) die("filename non trovato");
  if(empty($_REQUEST["descrizione"])) die("descrizione non trovato");
  if(empty($_REQUEST["tipo"])) die("tipo non trovato");
 
  $conn = ConnettiAlDB();
  $idAccertamento=EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamento"]);
  $tipo=intval($_REQUEST["tipo"]);
  $descrizione=EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizione"]);
  $filename=EscapeIfNotEMptyOrNull($conn,$_REQUEST["filename"]);
  
  $stmt = $conn->prepare("INSERT INTO Documento (idAccertamento,filename,tipo,descrizione) Values (?,?,?,?)");
  $stmt->bind_param("isis",$idAccertamento,$filename,$tipo,$descrizione);
  $ok=$stmt->execute();
  header('Location: accertamento.php?idAccertamento='.$idAccertamento);
?>
