<?
	include 'base.php';
    RedirectSeMancaCookie();
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
    <h1>Cambio password</h1>
      <form action='docambiopassword.php'>
        <div class="form-group">
          <label for="exampleInputEmail1">Vecchia password</label>
          <input type="password" class="form-control" name="vecchiapassword" autofocus>
          <label for="exampleInputEmail1">Nuova password</label>
          <input type="password" class="form-control" name="nuovapassword1">
          <label for="exampleInputEmail1">Nuova password (ripetere)</label>
          <input type="password" class="form-control" name="nuovapassword2">
        </div>
        <button type="submit" class="btn btn-default">Cambia</button>
      </form>
</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
