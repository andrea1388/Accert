<?
 
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

  function RedirectSeMancaCookie() {
    session_name("accertV2");
    //session_set_cookie_params(3600);
    session_start();
    setcookie(session_name(),session_id(),time()+3600);
    if(!isset($_SESSION['idutente'])) {
      header('Location: login.php');
      die();
    }
  }
  function GeneraFormGroup($valore,$nomecampo,$placeholder,$readonly) {
    echo "<div class=\"form-group\">\n";
    echo "<label for=\"" .$nomecampo . "\" class=\"col-sm-2 control-label\">" . $placeholder. "</label>\n";
    echo "<div class=\"col-sm-10\">\n";
    GeneraCampoInput($valore,$nomecampo,$placeholder,$readonly);
    echo "</div>\n";
    echo "</div>\n";
  }

  function GeneraCampoInput($valore,$nomecampo,$placeholder,$readonly)
  {
    echo "<input type=\"text\" class=\"form-control\" id=\"" . $nomecampo . "\"";
    echo " placeholder=\"" .$placeholder. "\" name=\"".$nomecampo."\"";
    if(isset($valore)) echo " value='" .htmlentities($valore,ENT_QUOTES)."'";
    if($readonly) echo " readonly";
    echo ">\n";

  }

  function GeneraFormInput($valore,$nomecampo,$etichetta,$required,$readonly,$maxlength=0,$placeholder="")
  {
    echo "<div class='form-group'>\n";
    GeneraFormLabel($nomecampo,$etichetta);
    echo "<div class='col-sm-10'>\n";
    echo "<input type='text' class='form-control' id='id_".$nomecampo."' name='".$nomecampo."' ";
    if(isset($valore)) echo " value='" .htmlentities($valore,ENT_QUOTES)."'";
    if($required) echo " required";
    if($readonly) echo " readonly";
    if($maxlength>0) echo " maxlength=".$maxlength;
    if(isset($placeholder)) echo " placeholder='".$placeholder."'"; else echo " placeholder='".$etichetta."'";
    echo ">\n";
    echo "</div>\n";
    echo "</div>\n";
  }

  function GeneraFormFile($valore,$nomecampo,$etichetta,$required,$readonly)
  {
    echo "<div class='form-group'>\n";
    GeneraFormLabel($nomecampo,$etichetta);
    echo "<div class='col-sm-10'>\n";
    echo "<input type='file' class='form-control' id='id_".$nomecampo."' name='".$nomecampo."' placeholder='".$etichetta."'";
    if(isset($valore)) echo " value='" .htmlentities($valore,ENT_QUOTES)."'";
    if($required) echo " required";
    if($readonly) echo " readonly";
    echo ">\n";
    echo "</div>\n";
    echo "</div>\n";
  }

  function GeneraFormDate($valore,$nomecampo,$etichetta,$required,$readonly)
  {
    echo "<div class='form-group'>\n";
    GeneraFormLabel($nomecampo,$etichetta);
    echo "<div class='col-sm-10'>\n";
    echo "\t<input type='text' class='form-control' id='id_".$nomecampo."' name='".$nomecampo."' placeholder='".$etichetta."'";
    if(isset($valore)) echo " value='" .htmlentities($valore,ENT_QUOTES)."'";
    if($required) echo " required";
    if($readonly) echo " readonly";
    echo ">\n";
    echo "</div>\n";
    echo "</div>\n";
  }

  function GeneraFormSelect($valore,$nomecampo,$etichetta,$required,$readonly,$array)
  {
    echo "<div class='form-group'>\n";
    GeneraFormLabel($nomecampo,$etichetta);
    echo "<div class='col-sm-10'>\n";
    echo "<select class='form-control' id='id_".$nomecampo." 'name='".$nomecampo."'";
    if($required) echo " required";
    if($readonly) echo " disabled";
    echo ">\n";
    GeneraOption($valore,$array);
    echo "</select>\n";
    echo "</div>\n";
    echo "</div>\n";
  }
  function GeneraFormSubmit($etichetta)
  {
    echo "<div class='form-group'>\n";
    echo "<div class='col-sm-offset-2 col-sm-10'>\n";
    echo "<button type='submit' class='btn btn-default'>".$etichetta."</button>\n";
    echo "</div>\n";
    echo "</div>\n";
  }
  function GeneraFormTextArea($valore,$nomecampo,$etichetta,$required,$readonly)
  {
    echo "<div class='form-group'>\n";
    GeneraFormLabel($nomecampo,$etichetta);
    echo "<div class='col-sm-10'>\n";
    echo "<textarea rows='4' cols='50' id='id_".$nomecampo."' class='form-control'  placeholder='".$etichetta."' name='".$nomecampo."'";
    if($required) echo " required";
    if($readonly) echo " readonly";
    echo ">\n";
    echo trim($valore); 
    echo "</textarea>\n";
    echo "</div>\n";
    echo "</div>\n";
  }

  function GeneraFormLabel($nomecampo,$etichetta)
  {
    echo "<label for='id_".$nomecampo."' class='col-sm-2 control-label'>".$etichetta."</label>\n";
  }


    
/**
 * Effettua la connessione al db, prendendo i parametri dal file db.inc.php
 * @return mixed connessione 
 */
  function ConnettiAlDB() {
    include('db.inc.php');
    $conn=ConnettiAlDBP($dbuser, $dbname);
    return $conn;
  }

  function ConnettiAlDBP($username, $database) {
    include('db.inc.php');
    $conn = new mysqli($host, $username, $dbpwd, $database);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    } 
    $conn->set_charset("utf8");
    return $conn;
  }

  function ConnettiAlDBUfficio() {
    include_once('db.inc.php');
    $conn=ConnettiAlDBP($dbuserUffici, $dbpwdUffici, $dbnameUffici);
    return $conn;
  }

  function logAttivita($conn, $logatt, $idpersona=0, $idaccertamento=0)
  {
    $logatt=substr($logatt, 0,99);
    $stmt = $conn->prepare("INSERT INTO Log (operazione) VALUES (?)");
    $stmt->bind_param("s", $logatt);
    $stmt->execute();
  }

  function EscapeIfNotEMptyOrNull($conn,$nullableval) {
    if(!isset($nullableval)) return NULL;
    //return ($nullableval != '') ? $conn->real_escape_string($nullableval) : NULL;
    return ($nullableval != '') ? $nullableval : NULL;
  }

  function StringaVuotaSeNonSettato($nullableval) {
    if(!isset($nullableval)) return "";
  }
  function IntValSeSettatoo0($nullableval) {
    if(!isset($nullableval)) return 0;
    return intval($nullableval);
  }
  function FormattaData($string,$format)
  {
    return ($string!=NULL) ? date($format, strtotime($string)) : '';

  }

  function ConvertiinHtml($nullableval)
  {
    if(!isset($nullableval)) return "";
    return htmlentities($nullableval, ENT_QUOTES);
  }

  function TipiAccertamento($conn)
  {
    $result = $conn->query("SELECT * FROM TipoAccertamento ORDER BY nome");
    $data_array = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $data_array[$row['idTipoAccertamento']] = $row['nome'];
    }
    return $data_array;
  }
  function SQLListaAccertamentiBase()
  {
    return "distinct t2.data, t2.idAccertamento, t4.nome tipo, numero, anno, luogo, descrizione ,GROUP_CONCAT(distinct t1.nome ) aa from Accertamento t2 left join SoggettoAccertamento t3 on t3.idAccertamento=t2.idAccertamento left join Soggetto t1 on t1.idSoggetto=t3.idSoggetto left join TipoAccertamento t4 on t2.idTipoAccertamento=t4.idTipoAccertamento";
  }

  function SQLListaAccertamentiConTipo()
  {
    return "select ".SQLListaAccertamentiBase()." where ((t1.nome like ? or t2.descrizione like ? or t2.luogo like ? or t2.targa like ?) and (t2.anno like ?) and (t4.idTipoAccertamento = ?)) group by t2.idAccertamento order by numero desc";
  }
  function SQLListaAccertamentiSenzaTipo()
  {
    return "select ".SQLListaAccertamentiBase()." where ((t1.nome like ? or t2.descrizione like ? or t2.luogo like ? or t2.targa like ?) and (t2.anno like ?) ) group by t2.idAccertamento order by numero desc";
  }


  function GeneraRisultatiTabellaListaAccertamenti($result)
  {
    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr onclick=\"window.document.location='accertamento.php?idAccertamento=".$row["idAccertamento"]."'\";>".
          "<td>".$row["tipo"]."</td>". 
          "<td>".$row["numero"]."</td>".
          "<td>".$row["anno"]."</td>".
          "<td>".date('d/m/Y',strtotime($row["data"]))."</td>".
          "<td>".$row["luogo"]."</td>". 
          "<td>".$row["descrizione"]."</td>". 
          "<td>".$row["aa"]."</td></tr>\n";
      }
    }
  }
  function QueryAccertamentiDaSoggetto($conn,$idSoggetto)
  {
    $sql=$sql="SELECT * FROM listaAttività la JOIN SoggettoAccertamento sa on la.idAccertamento=sa.idAccertamento and sa.idSoggetto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idSoggetto);
    $stmt->execute();
    return $stmt->get_result();
  }

function GeneraOption($selectedValue,$array) {
  echo "<option value=''></option>\n";
  foreach( $array as $key => $value ){
    echo "<option value='".$key."'";
    if($key==$selectedValue) echo " selected";
    echo ">";
    echo $value;
    echo "</option>\n";
  }
}
function GetIfSet(&$a,$v)
{
  if(isset($_REQUEST[$v])) $a=$_REQUEST[$v];
}
?>