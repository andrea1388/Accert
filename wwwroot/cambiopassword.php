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
    <h1 class="mt-3">Cambio password</h1>
      <form action='docambiopassword.php'>
        <div class="form-group mb-3">
          <label for="vecchiapassword" class="form-label">Vecchia password</label>
          <input type="password" class="form-control" name="vecchiapassword" id="vecchiapassword" autofocus>
        </div>
        <div class="form-group mb-3">
          <label for="nuovapassword1" class="form-label">Nuova password</label>
          <input type="password" class="form-control" name="nuovapassword1" id="nuovapassword1">
        </div>
        <div class="form-group mb-3">
          <label for="nuovapassword2" class="form-label">Nuova password (ripetere)</label>
          <input type="password" class="form-control" name="nuovapassword2" id="nuovapassword2">
        </div>
        <button type="submit" class="btn btn-primary">Cambia</button>
      </form>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
