<?
    $sql="SELECT count(*) FROM Soggetto";
    $result = $conn->query($sql);
    $row = $result->fetch_row();
    printf("Elementi registrati: Soggetti: %u ", $row[0]);
    $sql="SELECT count(*) FROM Accertamento";
    $result = $conn->query($sql);
    $row = $result->fetch_row();
    printf("Attività: %u ", $row[0]);
    $sql="SELECT count(*) FROM SoggettoAccertamento WHERE ruolo<>1";
    $result = $conn->query($sql);
    $row = $result->fetch_row();
    printf("Relazioni: %u\n", $row[0]);
?>

