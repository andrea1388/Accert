<table class="table table-hover table-bordered">
  <thead>
    <tr class="info">
      <td>Numero</td>
      <td>Data</td>
      <td>Tipo</td>
      <td>Descrizione</td>
      <td>Soggetti coinvolti</td>
    </tr>
  </thead>
  <tbody>
<?
    $param="";
    $array = array();
    $c=$_REQUEST["dati"];
    $anno=$_REQUEST["anno"];
    $tipo=$_REQUEST["tipo"];
    $sql="SELECT * FROM listaAttività";
    $wp="";
    if(!empty($c)) {
        $c="%".$c."%";
        array_push($array ,$c);
        array_push($array ,$c);
        array_push($array ,$c);
        array_push($array ,$c);
        $param=$param."ssss";
        $wp=$wp."(descrizione like ? or descrizione_estesa like ? or luogo like ? or soggetti like ?)";
    }
    if(!empty($anno)) {
        array_push($array ,$anno);
        $param=$param."i";
        if(!empty($wp)) $wp=$wp." and ";
        $wp=$wp."(anno = ?)";
    }
    if(!empty($tipo)) {
        array_push($array ,$tipo);
        $param=$param."i";
        if(!empty($wp)) $wp=$wp." and ";
        $wp=$wp."(idTipoAccertamento = ?)";
    }
    if(!empty($wp)) $sql=$sql." WHERE ".$wp;
    $sql=$sql." ORDER BY data desc";
/*     echo $sql."/".$param."<br>";
    print_r($array); */
  
    $stmt = $conn->prepare($sql);
    if(!empty($param)) $stmt->bind_param($param, ...$array);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <? while($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><a href='accertamento.php?idAccertamento=<? echo $row["idAccertamento"]; ?>'>
            <? echo $row["numeroAccertamento"]; ?></a></td>
            <td><?php echo FormattaData($row['data'],"d/m/Y"); ?></td>
            <td><?php echo $row["tipo"]; ?></td>
            <td><?php echo $row["descrizione"]; ?></td>
            <td><?php echo $row["soggetti"]; ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table> 
