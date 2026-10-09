<?
/**
 * crea o oggiorna una nuova attività
 * richiede i seguenti parametri
 * idAccertamento (se null è una nuova attività da creare)
 * idAccertamentoPadre NULL
 * idOperatore !NULL
 * luogo NULL
 * descrizione !NULL
 * descrizione_estesa NULL
 * targa NULL
 * anno !NULL
 * idTipoAccertamento !NULL
 * data NULL
 * ora NULL
 * ruolo !NULL
 * descRuolo NULL
 * 
 * Può essere chiamato dalla pagina accertamento 
 * - per creare un nuovo accertamento, con idAccertamento=NULL, se ok redirige ad accertamento passando nuovo id
 * - per modificare un aggiornamento, con idAccertamento !NULL e idAccertamentoPadre NULL se ok redirige ad accertamento passando vecchio id
 * - per creare una nuova sottoattività, con idAccertamentoPadre !NULL, se ok redirige ad accertamento passando idAccertamentoPadre
 */
	include 'base.php';
  RedirectSeMancaCookie();
    
  $ok=true;
  $conn = ConnettiAlDB();
  
  // imposta variabili da salvare
  $luogo=EscapeIfNotEMptyOrNull($conn,$_REQUEST["luogo"]);
  $descrizione=EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizione"]);
  $descrizione_estesa=EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizione_estesa"]);
  if(isset($_REQUEST["targa"])) $targa=EscapeIfNotEMptyOrNull($conn,$_REQUEST["targa"]);
  $anno=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["anno"]));
  $idTipoAccertamento=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["idTipoAccertamento"]));
  $idOperatore=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["idOperatore"]));
  $ruolo=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["ruolo"]));
  if(isset($_REQUEST["descRuolo"])) $descRuolo=EscapeIfNotEMptyOrNull($conn,$_REQUEST["descRuolo"]);
  if(isset($_REQUEST["idAccertamento"])) $idAccertamento=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamento"])); else $idAccertamento=0;
  if(isset($_REQUEST["idAccertamentoPadre"]))
  $idAccertamentoPadre=intval(EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamentoPadre"]));
  try {
    if(!empty($_REQUEST["Data"])) {
      $ora="0:00";
      $in=$_REQUEST["Data"];
      if(!empty($_REQUEST["Ora"])) $ora=$_REQUEST["Ora"];
      $in=$in." ".$ora;
      $escin=EscapeIfNotEMptyOrNull($conn,$in);
      $data = DateTime::createFromFormat("d/m/Y G:i",$escin);
      //echo $data;
      $data=$data->format("Y-m-d H:i");
    } 
  } catch (Exception $e) {
    $ok=false;
    $errore="Data/ora errata";
  }

  // controlli comuni
  if(empty($descrizione)) {$ok=false; $errore="Inserire descrizione";};
  if($anno<2000 || $anno>2099) {$ok=false; $errore="Inserire l'anno";};
  if($idTipoAccertamento==0) {$ok=false; $errore="Inserire tipo pratica";};

  if($ok) {
    if($idAccertamento>0) 
    {
      $stmt = $conn->prepare("UPDATE Accertamento SET data=?, luogo=?, descrizione=?, descrizione_estesa=?, targa=? where idAccertamento=?");
      $stmt->bind_param("sssssi", $data,$luogo,$descrizione,$descrizione_estesa,
      $targa,$idAccertamento);
      $ok=$stmt->execute();
      $errore=$stmt->error;
      $idRitorno=$idAccertamento;
    } else
    {
      $sql="CALL spCreaAccertamento(?,?,?,?,?,?,?,?,?,?,?,@id);";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("iisssssiiis", $idAccertamentoPadre, $anno, $targa, $luogo, $data,$descrizione,$descrizione_estesa,$idTipoAccertamento,$idOperatore,$ruolo,$descRuolo);
      $ok=$stmt->execute();
      $errore=$stmt->error;
      if($ok) {
        $sql="SELECT @id as id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $idNuovoAccertamento=$row['id'];
        if($idAccertamentoPadre>0) $idRitorno=$idAccertamentoPadre; else $idRitorno=$idNuovoAccertamento;
      }
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
      <h1 class="mt-3">Esito</h1>
      <? if($ok) :?>
      <p>Salvataggio effettuato</p>
      <button type="button" class="btn btn-secondary" onclick="window.location='accertamento.php?idAccertamento=<? echo $idRitorno;?>'">Torna all'attivit&agrave;</button>   
      <? else: ?> 
      <p>Errore</p>
      <p><? echo $errore;?></p>
      <button type="button" class="btn btn-secondary" onclick="javascript:history.go(-1)">Torna indietro</button>   
      <? endif; ?> 
	  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>