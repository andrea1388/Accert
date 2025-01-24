<table class="table table-hover table-bordered">
  <thead>
    <tr class="info">
      <td class="h3">Attività da compiere</td>
    </tr>
  </thead>
  <tbody>
<?
// attività, pratica, soggetti
/* function TableRowAttivitàDaCompletare($conn,$idOperatore)
{ */
  $sql="select * from listaAttività2 l join SoggettoAccertamento s on l.idAccertamento=s.idAccertamento where s.idSoggetto=? and data is null";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION['idutente']);
  $stmt->execute();
  $result = $stmt->get_result();
  $a="";
  $b="";
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) 
    {
        $a="<b>".$row["descrizione"]."</b>";
        if(!is_null($row["responsabili"])) $a=$a." (".$row["responsabili"].")";
        
        $b="";
        if(!is_null($row["idAccertamentoPadre"]))
        {
          $b="[".$row["numeroAccertamento"]."] ";
          $b=$b.$row["descrizionePadre"];
          if(!is_null($row["responsabiliPadre"])) $b=$b." (".$row["responsabiliPadre"].")";
        }

        echo "<tr><td>\n";
        echo "<a href='accertamento.php?idAccertamento=".$row["idAccertamento"]."'>".$a."</a>\n";
        
        if(!is_null($row["idAccertamentoPadre"]))
          echo " - <a href='accertamento.php?idAccertamento=".$row["idAccertamentoPadre"]."'>".$b."</a>\n"; 
    
        echo "</td></tr>\n";
    }
  }

?>
  </tbody>
</table>