<table class="table table-hover table-bordered">
<tr class="success"><td>Soggetto</td><td>Tel</td><td>Ruolo</td><td>Note</td><td>Azioni</td></tr>
<?
    // ruolo 1= accertatore 2 =resposnsabile 3 pif 4 altro
    $stmt = $conn->prepare("SELECT * FROM Soggetto join SoggettoAccertamento on Soggetto.idSoggetto=SoggettoAccertamento.idSoggetto join RuoloSoggetto on SoggettoAccertamento.ruolo=RuoloSoggetto.idRuolo where idAccertamento=?");
    $stmt->bind_param("i", $_REQUEST["idAccertamento"]);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) 
    {
      echo "<tr><td>".$row['nome']."</td><td>".$row['tel'].
      "</td><td>" .$row['nomeRuolo']. "</td><td>".$row['descRuolo'].
      "</td><td><a href='eliminaSoggettoAccertamento.php?idSoggettoAccertamento=".$row['idSoggettoAccertamento']."&idAccertamento=".$id."'>Elimina</a>";
      echo "&nbsp;<a href='soggetto.php?idSoggetto=".$row['idSoggetto']."'>Apri</a></td></tr>\n";
    }
?>
</table>
