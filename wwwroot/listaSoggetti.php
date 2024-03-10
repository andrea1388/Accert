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
        <h1>Lista soggetti corrispondenti</h1>
        
        <table class="table table-hover table-bordered">
        <thead>
        <tr class="success"><td>Nome</td><td>Indirizzo</td><td>Telefono</td></tr>
        </thead>
        <tbody>
        <?
            $conn = ConnettiAlDB();        
            $idaccertamento=isset($_REQUEST["idAccertamento"])? intval($_REQUEST["idAccertamento"]) : 0;
            $ruolo=isset($_REQUEST["ruolo"])? intval($_REQUEST["ruolo"]) : 0;
            $descrizioneruolo=isset($_REQUEST["descrizioneruolo"])? EscapeIfNotEMptyOrNull($conn,$_REQUEST["descrizioneruolo"]) : NULL;
            $c="%" . EscapeIfNotEMptyOrNull($conn,$_REQUEST['dati']) . "%";
            $sql = "SELECT * FROM Soggetto WHERE (nome like ? or societa like ? or residenza like ? or tel like ? or note like ? or luogonascita like ? or indirizzo like ? or mail like ? or documento like ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $c,$c,$c,$c,$c,$c,$c,$c,$c);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    if(!empty($idaccertamento))
                        echo "<tr onclick=\"window.document.location='AggiungiSoggettoAccertamento.php?idSoggetto=".$row["idSoggetto"]."&idAccertamento=".$idaccertamento."&ruolo=".$ruolo."&descrizioneruolo=".$descrizioneruolo."'\";>";
                    elseif(!empty($_REQUEST["elimina"]))
                        echo "<tr onclick=\"window.document.location='eliminaSoggetto.php?idSoggetto=".$row["idSoggetto"]."'\";>";
                    else
                        echo "<tr onclick=\"window.document.location='soggetto.php?idSoggetto=".$row["idSoggetto"]."'\";>";
                    echo "<td>" . $row["nome"]. "</td><td>" . $row["residenza"]. " - " . $row["indirizzo"]. "</td><td>" . $row["tel"] . "</td></tr>\n";

                }
            } else {

            }
            $conn->close();
        ?>
        </tbody>
        </table>
        
        <? if(!empty($idaccertamento)) :?>
        <div class="form-group">
            <button type="button" class="btn btn-primary" 
            onclick="window.location='soggetto.php?edit&idAccertamento=<? echo $idaccertamento;?>&ruolo=<? echo $ruolo; ?>&nome=<? echo $_REQUEST['dati']; ?>&descrizioneruolo=<? echo $descrizioneruolo; ?>'">
            Crea nuovo soggetto
            </button>    
        </div>
        <? else : ?>
        <br>
        <button type="button" class="btn btn-primary" onclick="window.location='soggetto.php'">Nuovo soggetto</button>    
        <?php endif; ?> 
	</div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>
