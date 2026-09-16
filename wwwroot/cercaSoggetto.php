<?
	include 'base.php';
    RedirectSeMancaCookie();
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista soggetti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
    <? include 'menu.php'; ?>
    <h1 class="mt-3">Cerca soggetto</h1>
      <form action='listaSoggetti.php'>
        <input type="hidden" name="elimina" value="<? echo isset($_REQUEST["elimina"])? 1 : 0; ?>">
        <div class="form-group mb-3">
          <label for="dati" class="form-label">Dati</label>
          <input type="text" class="form-control" id="dati" placeholder="Dati (nome, cognome, ditta, indirizzo, ecc.)" name="dati" autofocus>
        </div>
        <button type="submit" class="btn btn-primary">Cerca</button>
      </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
