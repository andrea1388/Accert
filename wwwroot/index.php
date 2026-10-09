<?
	include 'base.php';
  RedirectSeMancaCookie();
  $conn = ConnettiAlDB();
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <? include 'menu.php'; ?>

      <div class="card mb-3">
        <div class="card-body">
        <h4>Introduzione</h4>
        <p>Accert permette di associare soggetti a pratiche. Per iniziare ad utilizzarlo consulta la guida a questo indirizzo <a href="https://policeapps.acsoft.top/guida" target="_blank">policeapps.acsoft.top/guida</a></p>
        <h4>Statistiche</h4>
        <p><? include 'table.riepilogo.php'; ?></p>
        </div>
      </div>
      
      <div class="card mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2"><button type="button" class="btn btn-sm btn-success" onclick="window.location='soggetto.php'">Nuovo soggetto</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm btn-success" onclick="window.location='cercaSoggetto.php'">Cerca soggetto</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm btn-primary" onclick="window.location='accertamento.php'">Nuova pratica</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm btn-primary" onclick="window.location='cercaAccertamento.php'">Cerca pratica</button></div>
          </div> 
        </div>
      </div>
      
      <? include 'table.attivitàDaFare.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
