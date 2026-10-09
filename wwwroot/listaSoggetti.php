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
        <h1 class="mt-3">Lista soggetti corrispondenti</h1>
        
        <table class="table table-hover table-bordered">
        <thead>
        <tr class="table-success"><td>Nome</td><td>Indirizzo</td><td>Telefono</td></tr>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
