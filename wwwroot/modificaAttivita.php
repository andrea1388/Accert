<?
    class Attivita {
        public $idAccertamento;
        public $idAttivita;
        public $attivita;
        public $data;
        public $dataScadenza;
        public $descrizione;
    }

	include 'base.php';
    RedirectSeMancaCookie();
    $conn = ConnettiAlDB();
    $idAttivita=isset($_REQUEST["idAttivita"])? intval($_REQUEST["idAttivita"]) : 0;
    if($idAttivita==0) die("manca idatt");
    $stmt = $conn->prepare("SELECT * FROM Attivita where idAttivita=?");
    $stmt->bind_param("i", $idAttivita);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) die("id non trovato");
    $att=$result->fetch_object("Attivita");
    $conn->close();
    
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attivit&agrave;</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1>Attivit&agrave;</h1>
    <div id="datigenerali">
    <form class="form-horizontal" method="post" action="salvaAttivita.php">
      <input type="hidden" name="idAttivita" value="<?echo $idAttivita; ?>">
      <input type="hidden" name="idAccertamento" value="<?echo $att->idAccertamento; ?>">
      <? GeneraFormGroup(htmlspecialchars($att->descrizione),"descrizione","Descrizione",false); ?>
      <? GeneraFormGroup(FormattaData($att->data,"d/m/Y"),"data","Data completamento",false); ?>
      <? GeneraFormGroup(FormattaData($att->dataScadenza,"d/m/Y"),"dataScadenza","Data scadenza",false); ?>
      <div class="form-group">
            <label for="Descrizioneestesa" class="col-sm-2 control-label">Descrizione estesa</label>
            <div class="col-sm-10">
            <textarea rows="4" cols="50" id="Descrizioneestesa" class="form-control"  placeholder="Descrizione estesa" name="attivita"><? echo trim($att->attivita); ?></textarea>
            </div>
        </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Salva</button>
          <button type="button" class="btn btn-default" onclick="window.location='accertamento.php?idAccertamento=<? echo $att->idAccertamento;?>'">Torna all'accertamento</button>    
        </div>
      </div>
    </form>
    </div>

	</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
