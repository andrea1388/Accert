<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Home</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="logout.php">Esci</a></li>
        <li><a href="https://policeapps.acsoft.top" target='_blank'>Help</a></li>
        <li><a href="cambiopassword.php">Cambio password</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pratiche <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="accertamento.php">Nuova</a></li>
            <li><a href="cercaAccertamento.php">Cerca</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Soggetto <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="soggetto.php">Nuovo</a></li>
            <li><a href="cercaSoggetto.php">Cerca</a></li>
            <li><a href="cercaSoggetto.php?elimina=1">Elimina</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>