<?  
    include 'base.php';
    RedirectSeMancaCookie();

  if(!empty($_REQUEST["idAccertamento"])) {
    $conn = ConnettiAlDB();
    $id=EscapeIfNotEMptyOrNull($conn,$_REQUEST["idAccertamento"]);
    $d=new DateTime();
  } 
  else 
  {
    die("id mancante");
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
    <h1 class="mt-3">Aggiunta documento</h1>
    <div id="datigenerali">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
        <input type="hidden" name="tipo" value="1">

        <div class="form-group row">
          <label for="fileToUpload" class="col-sm-2 col-form-label">File</label>
          <div class="col-sm-10">
          <input type="file" class="form-control" name="file" id="fileToUpload">
        </div>
      </div>


      <? GeneraFormGroup($d->format("d/m/Y"),"data","Data documento",false); ?>
      <? GeneraFormGroup("","descrizione","Descrizione",false); ?>
      <!--
      <div class="form-group">
          <label for="Descrizioneestesa" class="col-sm-2 control-label">Descrizione estesa</label>
          <div class="col-sm-10">
          <select>
          <option value="volvo">Volvo</option>
          <option value="saab">Saab</option>
          <option value="opel">Opel</option>
          <option value="audi">Audi</option>
        </select>
        </div>
      </div>
      -->


      
      <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Salva</button>
          <button type="button" class="btn btn-outline-secondary" onclick="window.location='accertamento.php?idAccertamento=<? echo $id;?>'">Torna all'accertamento</button>    
          <button type="button" class="btn btn-outline-secondary" onclick="window.location='index.php'">Home</button>    
        </div>
      </div>
    </form>
    </div>

	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
