<table class="table table-hover table-bordered">
<tr class="warning"><td>Descrizione</td><td>Azioni</td></tr>
<?
    $stmt = $conn->prepare("SELECT * FROM Documento where idAccertamento=?");
    $stmt->bind_param("i", $idAccertamento);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
      //echo "<tr><td>".$row['filename']."</td><td>".$row['descrizione']."</td><td>".date("G:i:s",strtotime($row['dataDocumento']))."</td>\n";
      echo "<tr>\n";
      // echo "<td><a target='_blank' href='download.php?idDocumento=".$row['idDocumento']."'>Apri</a>&nbsp;";
      echo "<td><a target='_blank' href='".$row['filename']."'>".$row['descrizione']."</a></td>\n";
      echo "<td><a href='eliminaDocumentoAccertamento.php?idDocumento=".$row['idDocumento']."&idAccertamento=".$idAccertamento."'>Elimina</a></td></tr>";
    }
    $conn->close();
?>
</table>