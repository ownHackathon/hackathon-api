<template>
  <header>
    <nav class="navbar navbar-expand-lg bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="/">(Black) Hackathon</a>
        <button class="navbar-toggler text-white-50 fas fa-bars" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <ul class="navbar-nav d-flex">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span>Event </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="/event">Allgemeine Informationen</a></li>
                  <li><a class="dropdown-item" href="/event/list">Event Übersicht</a></li>
                  <li><a class="dropdown-item" href="/topic/submit">Thema vorschlagen</a></li>
                </ul>
              </li>
            </ul>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/team">Team</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#blog">Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#about">Über uns</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  name: "TheHeader"
}
</script>

<style lang="scss">
header {
  margin-bottom: 10px;
}
.nav-link {
  color: #f0f0f0;
}

.nav-item > a:hover, .nav-item > a:focus, .nav-item > a > span:hover, .nav-item > a > span:focus {
  color: #f0f0f0;
  text-decoration-color: #a52834;
  text-decoration-line: underline;
  text-decoration-thickness: 3px;
}
</style>
