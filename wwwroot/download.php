<?  
  include 'base.php';
  RedirectSeMancaCookie();
  if(empty($_REQUEST["idDocumento"])) die("id non trovato");
  $conn = ConnettiAlDB();
  $id=EscapeIfNotEMptyOrNull($conn,$_REQUEST["idDocumento"]);
  $stmt = $conn->prepare("SELECT * FROM Documento WHERE idDocumento=?");
  $stmt->bind_param("i", $id);
  $ok=$stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows == 0) die("id non trovato");
  $row = $result->fetch_assoc();
  $idAccertamento=$row['idAccertamento'];
  $fp=$bucketpath.$dbname.'/'.$idAccertamento.'/';
  $fn=$fp.$row['idDocumento'].'-'.$row['filename'];
  header('Content-disposition: inline');
  header("Content-type: ".$row['conttype']);
  header("Content-Length: " . filesize($fn));
  readfile($fn);
?>
