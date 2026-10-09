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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attivit&agrave;</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1 class="mt-3">Attivit&agrave;</h1>
    <div id="datigenerali">
    <form method="post" action="salvaAttivita.php">
      <input type="hidden" name="idAttivita" value="<?echo $idAttivita; ?>">
      <input type="hidden" name="idAccertamento" value="<?echo $att->idAccertamento; ?>">
      <? GeneraFormGroup(htmlspecialchars($att->descrizione),"descrizione","Descrizione",false); ?>
      <? GeneraFormGroup(FormattaData($att->data,"d/m/Y"),"data","Data completamento",false); ?>
      <? GeneraFormGroup(FormattaData($att->dataScadenza,"d/m/Y"),"dataScadenza","Data scadenza",false); ?>
      <div class="form-group row">
            <label for="Descrizioneestesa" class="col-sm-2 col-form-label">Descrizione estesa</label>
            <div class="col-sm-10">
            <textarea rows="4" cols="50" id="Descrizioneestesa" class="form-control"  placeholder="Descrizione estesa" name="attivita"><? echo trim($att->attivita); ?></textarea>
            </div>
        </div>
      <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Salva</button>
          <button type="button" class="btn btn-outline-secondary" onclick="window.location='accertamento.php?idAccertamento=<? echo $att->idAccertamento;?>'">Torna all'accertamento</button>    
        </div>
      </div>
    </form>
    </div>

	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
