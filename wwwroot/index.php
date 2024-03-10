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
    <title>Accert</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <? include 'menu.php'; ?>

      <div class="panel panel-default">
        <div class="panel-body">
        <h4>Introduzione</h4>
        <p>Accert permette di associare soggetti a pratiche. Per iniziare ad utilizzarlo consulta la guida a questo indirizzo <a href="https://policeapps.acsoft.top/guida" target='_blank'>policeapps.acsoft.top/guida</a></p>


        <h4>Donazioni</h4>
        <p>Se ti pace Accert puoi fare una donazione. Serve per sostenere i costi del cloud, per aggiungere nuove funzionalità e per garantire il monitoraggio del buon funzionamento del sistema.
        <form action="https://www.paypal.com/donate" method="post" target="_top">
          <input type="hidden" name="hosted_button_id" value="VBBN68XBEQPFY" />
          <input type="image" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_donate_LG.gif" border="0" name="submit" title="Dona con PayPal" alt="Fai una donazione con il pulsante PayPal" />
          <img alt="" border="0" src="https://www.paypal.com/it_IT/i/scr/pixel.gif" width="1" height="1" />
        </form>
        </p>
        <h4>Statistiche</h4>
        <p><? include 'table.riepilogo.php'; ?></p>
        </div>
      </div>
      
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-md-2"><button type="button" class="btn btn-sm btn-success" onclick="window.location='soggetto.php'">Nuovo soggetto</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm  btn-success" onclick="window.location='cercaSoggetto.php'">Cerca soggetto</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm  btn-primary" onclick="window.location='accertamento.php'">Nuova pratica</button></div>

            <div class="col-md-2"><button type="button" class="btn btn-sm  btn-primary" onclick="window.location='cercaAccertamento.php'">Cerca pratica</button></div>
          </div> 
        </div>
      </div>
      

      <? include 'table.attivitàDaFare.php'; ?>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
