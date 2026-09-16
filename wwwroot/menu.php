<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="logout.php">Esci</a></li>
        <li class="nav-item"><a class="nav-link" href="https://policeapps.acsoft.top" target="_blank">Help</a></li>
        <li class="nav-item"><a class="nav-link" href="cambiopassword.php">Cambio password</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPratiche" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pratiche</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownPratiche">
            <li><a class="dropdown-item" href="accertamento.php">Nuova</a></li>
            <li><a class="dropdown-item" href="cercaAccertamento.php">Cerca</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSoggetto" role="button" data-bs-toggle="dropdown" aria-expanded="false">Soggetto</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownSoggetto">
            <li><a class="dropdown-item" href="soggetto.php">Nuovo</a></li>
            <li><a class="dropdown-item" href="cercaSoggetto.php">Cerca</a></li>
            <li><a class="dropdown-item" href="cercaSoggetto.php?elimina=1">Elimina</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>