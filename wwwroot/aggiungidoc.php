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
    <h1>Aggiunta documento</h1>
    <div id="datigenerali">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idAccertamento" value="<? echo $id; ?>">
        <input type="hidden" name="tipo" value="1">

        <div class="form-group">
          <label for="fileToUpload" class="col-sm-2 control-label">File</label>
          <div class="col-sm-10">
          <input type="file" name="file" id="fileToUpload">
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


      
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Salva</button>
          <button type="button" class="btn btn-default" onclick="window.location='accertamento.php?idAccertamento=<? echo $id;?>'">Torna all'accertamento</button>    
          <button type="button" class="btn btn-default" onclick="window.location='index.php'">Home</button>    
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
