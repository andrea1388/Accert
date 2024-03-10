<?  
  class Accertamento {
    public $idAccertamento;
    public $numero;
    public $anno;
    public $idTipoAccertamento;
    public $data;
    public $luogo;
    public $descrizione;
    public $descrizione_estesa;
    public $targa;
    public $idAccertamentoPadre;
  }

	include 'base.php';
  RedirectSeMancaCookie();
  $conn = ConnettiAlDB();
  $arrayTipiAccertamento=TipiAccertamento($conn);

  if(!empty($_REQUEST["idAccertamento"])) {
    $id=EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamento"]);
    $stmt = $conn->prepare("SELECT * FROM Accertamento where idAccertamento=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      die("id non trovato");
    };
    $acc=$result->fetch_object("Accertamento");
    $nuovo=false;
    $readonly=!isset($_REQUEST["edit"]);
    $idAccertamento=$_REQUEST["idAccertamento"];
  } 
  else 
  {
    $nuovo=true;
    $readonly=false;
    $acc=new Accertamento();
    $d=new DateTime();
    $acc->data=$d->format("Y-m-d G:i");
    $acc->anno=$d->format("Y");
    $id=0;
    $idTipoAccertamento=0;
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
      <h1>Accertamento</h1>
      <div class="alert alert-default" >
        <form class="form-horizontal" method="get" action="salvaAttività.php">
          <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
          <input type="hidden" name="idOperatore" value="<? echo $_SESSION['idutente']; ?>">
          <input type="hidden" name="ruolo" value="1">
          <input type="hidden" name="descRuolo" value="Creatore">
          <? GeneraFormSelect($acc->idTipoAccertamento,"idTipoAccertamento","Tipo Attività",True,$readonly,$arrayTipiAccertamento); ?>
          <? GeneraFormInput($acc->numero,"numero","Numero",true,true); ?>
          <? GeneraFormInput($acc->anno,"anno","Anno",true,!$nuovo); ?>
          <div class="form-group">
              <label for="Data" class="col-sm-2 control-label">Data/Ora</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="Data" placeholder="lasciare vuoto se è ancora da fare" name="Data" value="<? echo FormattaData($acc->data,"d/m/Y"); ?>" <? if($readonly) echo " readonly";?>>
              </div>
              <div class="col-sm-5">
                <input type="text" class="form-control" id="Ora" placeholder="Ora" name="Ora"  value="<? echo FormattaData($acc->data,"G:i"); ?>" <? if($readonly) echo " readonly";?>>
              </div>
          </div>
          <? GeneraFormInput($acc->luogo,"luogo","Luogo",false,$readonly); ?>
          <? GeneraFormInput($acc->descrizione,"descrizione","Descrizione",true,$readonly); ?>
          <? GeneraFormTextArea(trim($acc->descrizione_estesa),"descrizione_estesa","Descrizione estesa",false,$readonly); ?>
          <? //GeneraFormInput($acc->targa,"targa","Targa",false,$readonly); ?>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Salva</button>
              <? if($readonly) :?>
              <button type="button" class="btn btn-default" onclick="window.location='accertamento.php?edit&idAccertamento=<? echo $id;?>'">Abilita modifiche</button>    
              <?php endif; ?> 
              <? if(!empty($acc->idAccertamentoPadre)) :?>
              <button type="button" class="btn btn-default" onclick="window.location='accertamento.php?idAccertamento=<? echo $acc->idAccertamentoPadre;?>'">Vai alla pratica principale</button>    
              <?php endif; ?> 
            </div>
          </div>
  </form>

      </div>

      <? if(!$nuovo) :?>
      <h2>Soggetti</h2>
      <?
        include 'table.soggetti.php';
      ?>

      <h2>Elenco attività</h2>
      <?
        include 'table.subattività.php';
      ?>
      <h2>Elenco documenti esterni</h2>
      <?
        include 'table.documenti.php';
      ?>
      <hr>
      <h2>Aggiungi soggetto</h2>
      <form class="form-horizontal" method="post" action="listaSoggetti.php">    
        <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
        <? GeneraFormInput("","dati","Cognome",true,false,5,"Inserire parte del nome o del cognome per la ricerca"); ?>
        <?php
          $y =array("1" =>"1 - Accertatore o persona che ha creato o deve eseguire la pratica","2" =>"2 - Soggetto cui si riferisce la pratica (trasgressori, destinatari notifica, persona controllata, ricorrente, ecc.)","3" =>"3 - Altri soggetti associati alla pratica (pif, pm, periti, difensori, ecc.)");
          GeneraFormSelect("","ruolo","Ruolo",true,false,$y)
        ?>
        <? GeneraFormInput("","descrizioneruolo","descrizione ruolo",false,false); ?>
        <? GeneraFormSubmit("Cerca"); ?>
      </form>

      <h2>Aggiungi attivit&agrave;</h2>
      <form class='form-horizontal' action="salvaAttività.php" method="post">
        <input type="hidden" name="idAccertamentoPadre" value="<? echo $id; ?>">
        <input type="hidden" name="anno" value="<? echo date("Y"); ?>">
        <input type="hidden" name="idOperatore" value="<? echo $_SESSION['idutente']; ?>">
        <input type="hidden" name="ruolo" value="1">
        <? GeneraFormSelect("","idTipoAccertamento","Tipo Attività",True,False,$arrayTipiAccertamento); ?>
        <? GeneraFormInput("","luogo","Luogo",false,False); ?>
        <? GeneraFormDate("","Data","Data",False,False); ?>
        <? GeneraFormInput("","descrizione","Descrizione",True,False); ?>
        <? GeneraFormTextArea("","descrizione_estesa","Descrizione estesa",False,False); ?>
        <? GeneraFormSubmit("Aggiungi"); ?>
      </form> 
      <h2>Aggiungi collegamenti a documenti esterni (Google drive, dropbox, documenti sul web, ecc)</h2>
      <form class="form-horizontal" action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
        <input type="hidden" name="tipo" value="1">
        <? GeneraFormInput("","filename","Url",True,false,0,"inserire url completo"); ?>
        <? GeneraFormInput("","descrizione","Descrizione file",True,false); ?>
        <? GeneraFormSubmit("Aggiungi"); ?>
      </form>

      <?php endif; ?> 
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
