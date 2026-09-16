<?
  class Soggetto {
    public $nome;
    public $societa;
    public $dataNascita;
    public $luogoNascita;
    public $residenza;
    public $indirizzo;
    public $tel;
    public $mail;
    public $documento;
    public $idSoggetto;
    public $login;
    public $note;
  }
	include 'base.php';
  RedirectSeMancaCookie();
  $conn = ConnettiAlDB();
  if(isset($_SESSION["permessi"])) $permessi=$_SESSION["permessi"]; else $permessi="";
  $idaccertamento=isset($_REQUEST["idAccertamento"])? intval($_REQUEST["idAccertamento"]) : 0;
  $ruolo=isset($_REQUEST["ruolo"])? intval($_REQUEST["ruolo"]) : 0;
  $descrizioneruolo=isset($_REQUEST["descrizioneruolo"])? EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizioneruolo"]) : NULL;  
	if(!empty($_REQUEST["idSoggetto"])) {
    $stmt = $conn->prepare("SELECT * FROM Soggetto where idSoggetto=?");
    $stmt->bind_param("i", $_REQUEST["idSoggetto"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      die("id non trovato");
    };
    $sogg=$result->fetch_object("Soggetto");
    $nuovo=false;
    $readonly=!isset($_REQUEST["edit"]);
  } else {
      $nuovo=true;
      $sogg=new Soggetto();
      $dn="";
      $readonly=false;
      if(isset($_REQUEST["nome"])) $sogg->nome=$_REQUEST["nome"];
  }

    
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soggetto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <? include 'menu.php'; ?>
      <h1 class="mt-3">Soggetto</h1>
      <div id="datigenerali">
      <form method="post" action="salvaSoggetto.php">
        <input type="hidden" name="idSoggetto" value="<?echo $sogg->idSoggetto; ?>">
        <input type="hidden" name="idAccertamento" value="<?echo $idaccertamento; ?>">
        <input type="hidden" name="ruolo" value="<?echo $ruolo; ?>">
        <input type="hidden" name="descrizioneruolo" value="<?echo $descrizioneruolo; ?>">
        <? GeneraFormGroup($sogg->nome,"nome","Nome",$readonly); ?>
        <? GeneraFormGroup($sogg->societa,"societa","Societ&agrave;",$readonly); ?>
        <? GeneraFormGroup(FormattaData($sogg->dataNascita,"d/m/Y"),"dataNascita","Data di nascita",$readonly); ?>
        <? GeneraFormGroup($sogg->luogoNascita,"luogoNascita","Luogo di nascita",$readonly); ?>
        <? GeneraFormGroup($sogg->residenza,"residenza","Luogo di residenza o domicilio",$readonly); ?>
        <? GeneraFormGroup($sogg->indirizzo,"indirizzo","Indirizzo",$readonly); ?>
        <? GeneraFormGroup($sogg->tel,"tel","Telefono",$readonly); ?>
        <? GeneraFormGroup($sogg->mail,"mail","e-mail",$readonly); ?>
        <? GeneraFormGroup($sogg->documento,"documento","Documento",$readonly); ?>
        <? if((strpos($permessi, 'P') !== false)) :?>
        <? GeneraFormGroup($sogg->login,"login","login",$readonly); ?>
        <? GeneraFormGroup("","password","Password",$readonly); ?>
        <?php endif; ?>
        <? GeneraFormTextArea(trim($sogg->note),"note","Note",false,$readonly); ?> 
        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Salva</button>
            <? if($readonly) :?>
            <button type="button" class="btn btn-outline-secondary" onclick="window.location='soggetto.php?edit&idSoggetto=<? echo $_REQUEST["idSoggetto"];?>'">Abilita modifiche</button>    
            <?php endif; ?> 
          </div>
        </div>
      </form>
      </div>
      <? if(!$nuovo) :?>
      <div id="listaaccertamenti">
      <h1 class="mt-4">Lista accertamenti corrispondenti</h1>
      <table class="table table-hover table-bordered">
        <thead>
          <tr class="table-info">
            <td>Numero</td>
            <td>Tipo</td>
            <td>Data</td>
            <td>Descrizione</td>
            <td>Descrizione estesa</td>
            <td>Soggetti coinvolti</td>

          </tr>
        </thead>
        <tbody>
          <? $result=QueryAccertamentiDaSoggetto($conn,$_REQUEST["idSoggetto"]); ?>
          <? while($row = $result->fetch_assoc()) : ?>
          <tr>
              <td><a href='accertamento.php?idAccertamento=<? echo $row["idAccertamento"]; ?>'><? echo $row["numeroAccertamento"]; ?></td>
              <td><?php echo $row["tipo"]; ?></td>
              <td><?php echo date("d/m/Y",strtotime($row['data'])); ?></td>
              <td><?php echo $row["descrizione"]; ?></td>
              <td><?php echo $row["descrizione_estesa"]; ?></td>
              <td><?php echo $row["soggetti"]; ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table> 
      </div>
      <?php endif; ?> 
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
<?php $conn->close(); ?>