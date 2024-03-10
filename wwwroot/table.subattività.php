<table class="table table-hover table-bordered">
  <thead>
    <tr class="info">
      <td>Numero</td>
      <td>Tipo</td>
      <td>Data</td>
      <td>Descrizione</td>
      <td>Descrizione estesa</td>
      <td>Soggetti coinvolti</td>

    </tr>
  </thead>
  <tbody>
<?
  $sql="select * from listaAttività where idAccertamentoPadre=? order by data asc";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $idAccertamento);
  $stmt->execute();
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()) 
  {
      echo "<tr>\n";
      echo "<td><a href='accertamento.php?idAccertamento=".$row["idAccertamento"]."'>".$row["numeroAccertamento"]."</a></td>\n";
      echo "<td>".$row["tipo"]."</td>\n";
      echo "<td>".FormattaData($row['data'],"d/m/Y")."</td>\n";
      echo "<td>".$row["descrizione"]."</td>\n"; 
      echo "<td>".$row["descrizione_estesa"]."</td>\n"; 
      echo "<td>".$row["soggetti"]."</td>\n";
      echo "</tr>\n";
  }
?>
      </tbody>
</table> 
