<template>
  <header>
    <nav class="navbar navbar-expand-lg bg-dark">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">(Black) Hackathon</router-link>
        <button class="navbar-toggler text-white-50" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <font-awesome-icon icon="bars"></font-awesome-icon>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <ul class="navbar-nav d-flex">
              <li class="nav-item dropdown">
                <router-link class="nav-link dropdown-toggle" to="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span>Event </span>
                </router-link>
                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                  <li><router-link class="dropdown-item" to="/event">Allgemeine Informationen</router-link></li>
                  <li><router-link class="dropdown-item" to="/event/list">Event Übersicht</router-link></li>
                  <li><router-link class="dropdown-item" to="/topic/submit">Thema vorschlagen</router-link></li>
                </ul>
              </li>
            </ul>
            <li class="nav-item">
              <router-link class="nav-link active" to="/team" >Team</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link active" to="#blog">Blog</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link active" to="#about">Über uns</router-link>
            </li>
          </ul>
          <ul class="navbar-nav d-flex">
            <li class="nav-item dropdown">
              <router-link class="nav-link dropdown-toggle" to="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fas fa-user-lock"></span>
                <font-awesome-icon icon="user-lock"></font-awesome-icon>
                <span> </span>
              </router-link>
              <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarUserDropdown">
                <li class="text-center fw-bolder"></li>
                <li><router-link class="dropdown-item" to="/login">Anmelden</router-link></li>
                <li><router-link class="dropdown-item" to="/user/register">Registrieren</router-link></li>
              </ul>
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
