<?  

	include 'base.php';
  RedirectSeMancaCookie();

  if(!empty($_REQUEST["idAccertamento"])) {
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_type = $file ['type'];
    $file_size = $file ['size'];
    $file_path = $file ['tmp_name'];
    $conn = ConnettiAlDB();
    $id=EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamento"]);
    if(isset($_REQUEST["data"]))
      $datadoc = DateTime::createFromFormat("d/m/Y",EscapeIfNotEMptyOrNull($conn,$_REQUEST["data"]))->format("Y-m-d H:i:s");
    else
      $datadoc=NULL;
    $tipo=intval($_REQUEST["tipo"]);
    $descrizione=EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizione"]);
    if($file_name!="")
      if(move_uploaded_file ($file_path,'tmp/'.$file_name))
      {
  
        //$filename = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$file_name;
        $filename = 'tmp/'.$file_name;
        
        $handle = fopen($filename, "rb");
        if(!$handle) die("!fopen");
        $contents = fread($handle, filesize($filename));
        $ct=mime_content_type($filename);
        //echo $filename." ".filesize($filename)." ".$contents."<br>";
        $stmt = $conn->prepare("INSERT INTO Documento (File,idAccertamento,filename,dataDocumento,tipo,descrizione,conttype) Values (?,?,?,?,?,?,?)");
        $stmt->bind_param("sississ", $contents,$id,$file_name,$datadoc,$tipo,$descrizione,$ct);
        $ok=$stmt->execute();
        fclose($handle);
                        
        unlink($filename);
                
        if($ok)
        {
          header('Location: accertamento.php?idAccertamento='.$id);
          return;      
        } 
        
      } die("move failed");

  } 
  else 
  { 
    die("id non trovato");

  }

?>