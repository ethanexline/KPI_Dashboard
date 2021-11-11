<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom" id="top-bar">

  <button  class="btn btn-secondary" id="menu-toggle" onClick="util.fakeResize()">Toggle Menu</button>
  <nav  aria-label="breadcrumb" >
    <ol id="breadcrumb-container" class="breadcrumb" style="margin: auto !important; margin-left: 10px !important;">
      <!-- breadcumbs here -->
    </ol>
  </nav>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="./dashSettings"><span class="oi" data-glyph="person"></span> <?php echo $_SESSION["username"] ?> <span class="sr-only">(current)</span></a>
      </li>
      <li>
      <a class="nav-link" href="/oauthsso/logout"><span class="oi" data-glyph="account-logout"></span> Logout<span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>