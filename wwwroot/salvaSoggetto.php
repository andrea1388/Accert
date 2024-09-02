<?
  	include 'base.php';
    RedirectSeMancaCookie();

    $ok=true;
    $conn = ConnettiAlDB();
    if(isset($_SESSION["permessi"])) $permessi=$_SESSION["permessi"]; else $permessi="";
  

    // imposta variabili da salvare
    // queste sono settate solo se si proviene dal form accertamento e saranno passate alla aggiungisoggettoaccertamento
    $idaccertamento=isset($_REQUEST["idAccertamento"])? intval($_REQUEST["idAccertamento"]) : 0;
    $ruolo=isset($_REQUEST["ruolo"])? intval($_REQUEST["ruolo"]) : 0;
    $descrizioneruolo=isset($_REQUEST["descrizioneruolo"])? EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizioneruolo"]) : NULL;

    $id=intval($_REQUEST["idSoggetto"]);
    if($_REQUEST["dataNascita"] != '')
    {
      $dn = DateTime::createFromFormat("d/m/Y",EscapeIfNotEMptyOrNull($conn,$_REQUEST["dataNascita"]))->format("Y-m-d");
    } else $dn=NULL;
    $nome=EscapeIfNotEMptyOrNull($conn,$_REQUEST["nome"]);
    $nome=$_REQUEST["nome"];
    $societa=EscapeIfNotEMptyOrNull($conn,$_REQUEST["societa"]);
    $ln=EscapeIfNotEMptyOrNull($conn,$_REQUEST["luogoNascita"]);
    $re=EscapeIfNotEMptyOrNull($conn,$_REQUEST["residenza"]);
    $tel=EscapeIfNotEMptyOrNull($conn,$_REQUEST["tel"]);
    $mail=EscapeIfNotEMptyOrNull($conn,$_REQUEST["mail"]);
    $doc=EscapeIfNotEMptyOrNull($conn,$_REQUEST["documento"]);
    $ind=EscapeIfNotEMptyOrNull($conn,$_REQUEST["indirizzo"]);
    if((strpos($permessi, 'P') !== false)) {
      $login=EscapeIfNotEMptyOrNull($conn,$_REQUEST["login"]);
      $password=EscapeIfNotEMptyOrNull($conn,$_REQUEST["password"]);
      $pwdhash=password_hash($password, PASSWORD_BCRYPT);
    }
    $note=EscapeIfNotEMptyOrNull($conn,$_REQUEST["note"]);


    // controlli comuni
    if(empty($nome) && empty($societa)) {$ok=false; $errore="Inserire nome o Societ&agrave;";};
    if(!empty($login) && empty($password)) {$ok=false; $errore="Inserire password";};
    if(!empty($login) && empty($mail)) {$ok=false; $errore="Inserire mail";};
    
    
    
    if($ok) {
      if($id>0) {
        // controlli per update
        if((strpos($permessi, 'P') !== false) && isset($password)) {   
          $stmt = $conn->prepare("UPDATE Soggetto SET nome=?, dataNascita=?, luogoNascita=?, residenza=?, tel=?, mail=?, documento=?, indirizzo=?, societa=?, login=?, pwdhash=?, note=? where idSoggetto=?");
          $stmt->bind_param("ssssssssssssi", 
          $nome,
          $dn,
          $ln,
          $re,
          $tel,
          $mail,
          $doc,
          $ind,
          $societa,
          $login,
          $pwdhash,
          $note,
          $id);
          }
        else {
          $stmt = $conn->prepare("UPDATE Soggetto SET nome=?, dataNascita=?, luogoNascita=?, residenza=?, tel=?, mail=?, documento=?, indirizzo=?, societa=?, note=? where idSoggetto=?");
          $stmt->bind_param("ssssssssssi", 
          $nome,
          $dn,
          $ln,
          $re,
          $tel,
          $mail,
          $doc,
          $ind,
          $societa,
          $note,
          $id);
  
        }
        $ok=$stmt->execute();
        $errore=$stmt->error;
        
      } else {
        // controlli per insert
        // setup campi default
        $tiposoggetto=0;

        if((strpos($permessi, 'P') !== false)) {        
          $stmt = $conn->prepare("insert into Soggetto (nome, dataNascita, luogoNascita, residenza, tel, mail, documento, indirizzo, societa, tipo, login, pwdhash, note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmt->bind_param("sssssssssisss", 
          $nome,
          $dn,
          $ln,
          $re,
          $tel,
          $mail,
          $doc,
          $ind,
          $societa,
          $tiposoggetto,
          $login,
          $pwdhash,
          $note);
        } else {
          $stmt = $conn->prepare("insert into Soggetto (nome, dataNascita, luogoNascita, residenza, tel, mail, documento, indirizzo, societa, tipo,note) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
          $stmt->bind_param("sssssssssis", 
          $nome,
          $dn,
          $ln,
          $re,
          $tel,
          $mail,
          $doc,
          $ind,
          $societa,
          $tiposoggetto,
          $note);
        }
        $ok=$stmt->execute();
        $errore=$stmt->error;
        if($ok) 
          $id=$conn->insert_id;
          
      }
      if($ok)
        if($idaccertamento>0)
        {
          header("Location: AggiungiSoggettoAccertamento.php?idAccertamento=".$idaccertamento."&idSoggetto=".$id."&ruolo=".$ruolo."&descrizioneruolo=".$descrizioneruolo);
          $conn->close();
          die();
  
        }
        //else
        //  header("Location: soggetto.php?idSoggetto=".$id);
        $conn->close();      
    }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accertamento</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1>Soggetto</h1>
	<? if($ok) echo "Soggetto salvato id=". $id; else echo "Errore: Soggetto non salvato: " . $errore; ?>
	<button type="button" class="btn btn-default" onclick="window.location='index.php'">Home</button>    
	<button type="button" class="btn btn-default" onclick="window.location='soggetto.php?idSoggetto=<? echo $id;?>'">Torna al Soggetto</button>    
	
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>