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
  header('Content-disposition: inline');
  header("Content-type: ".$row['conttype']);
  $row = $result->fetch_assoc();
  header("Content-Length: " . strlen($row['File']));
  // header('Content-Disposition: attachment; filename="' . $row['filename'] . '"');
  echo $row['File'];
?>