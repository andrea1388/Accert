<!-- Cerca accertamenti
Campi di ricerca: tipo (tutti o uno specifico default tutti), anno (facoltativo), descrizione (facoltativo)
descrizione cerca tra: nome, descrizione, luogo targa, così come definito in lista accertamenti -->
<?
	include 'base.php';
  RedirectSeMancaCookie();
  $conn = ConnettiAlDB();
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista soggetti</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1>Cerca tra le pratiche</h1>
      <form action='listaAccertamenti.php'>



        
        <div class="form-group">
          <label for="idtesto">Dati</label>
          <input type="text" class="form-control" id="idtesto" placeholder="Dati (nome soggetto, luogo, descrizione, ecc.)" name="dati" autofocus>
        </div>

        <div class="form-group">
          <label for="idanno">Anno</label>
          <input type="text" class="form-control" id="idanno" placeholder="Anno" name="anno">
        </div>

        <div class="form-group">
            <label for="idtipo">Tipo di pratica</label>
            <select name='tipo' id ="idtipo" class="form-control">
              <?php
              $rows=TipiAccertamento($conn);
              GeneraOption("",$rows);
              ?>
            </select>
        </div>






        <button type="submit" class="btn btn-default">Cerca</button>
      </form>
</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
