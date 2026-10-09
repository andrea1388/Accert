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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accertamento</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <? include 'menu.php'; ?>
      <h1 class="mt-3">Accertamento</h1>
      <div class="alert alert-secondary">
        <form method="get" action="salvaAttività.php">
          <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
          <input type="hidden" name="idOperatore" value="<? echo $_SESSION['idutente']; ?>">
          <input type="hidden" name="ruolo" value="1">
          <input type="hidden" name="descRuolo" value="Creatore">
          <? GeneraFormSelect($acc->idTipoAccertamento,"idTipoAccertamento","Tipo Attività",True,$readonly,$arrayTipiAccertamento); ?>
          <? GeneraFormInput($acc->numero,"numero","Numero",true,true); ?>
          <? GeneraFormInput($acc->anno,"anno","Anno",true,!$nuovo); ?>
          <div class="form-group row">
              <label for="Data" class="col-sm-2 col-form-label">Data/Ora</label>
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
          <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
              <button type="submit" class="btn btn-primary">Salva</button>
              <? if($readonly) :?>
              <button type="button" class="btn btn-outline-secondary" onclick="window.location='accertamento.php?edit&idAccertamento=<? echo $id;?>'">Abilita modifiche</button>    
              <?php endif; ?> 
              <? if(!empty($acc->idAccertamentoPadre)) :?>
              <button type="button" class="btn btn-outline-secondary" onclick="window.location='accertamento.php?idAccertamento=<? echo $acc->idAccertamentoPadre;?>'">Vai alla pratica principale</button>    
              <?php endif; ?> 
            </div>
          </div>
</form>

      </div>

      <? if(!$nuovo) :?>
      <h2 class="mt-4">Soggetti</h2>
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
      <form method="post" action="listaSoggetti.php">    
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
      <form action="salvaAttività.php" method="post">
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
      <h2>Aggiungi documento</h2>
      <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
        <input type="hidden" name="tipo" value="1">
        <? GeneraFormFile("","file","File",True,false); ?>
        <? GeneraFormInput("","descrizione","Descrizione file",True,false); ?>
        <? GeneraFormSubmit("Aggiungi"); ?>
      </form>

      <?php endif; ?> 
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
