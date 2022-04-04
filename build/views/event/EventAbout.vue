<template>
  <div >
    <h3>Hackathon</h3>
    <h5>Worum geht es überhaupt?</h5>
    <p>
      Um einen Web-Application Wettbewerb im Zuge eines Hackathon (<a href="https://de.wikipedia.org/wiki/Hackathon" target="_blank">Wikipedia</a>).<br>
      Zu einem vorgegebenen Thema entwickelt ihr ein eigenes Projekt.<br>
      Hier in der Form einer Web-Application realisiert mit PHP und den dazugehörigen Webtechniken (Frameworks und andere Abhängigkeiten wie zum Beispiel
      swiftmailer etc. pp. sind explizit erlaubt).
    </p>
    <h5>Mitmachen</h5>
    <p>
      Mitmachen kann jeder mit Lust und Laune.<br>
      Ihr solltet aber zumindest soviel Erfahrungen mitbringen um ein Projekt in gegebener Zeit auf die Beine stellen zu können.<br>
      Überzeug deine Mitstreiter mit deiner Idee, damit sie ihre Stimme für dein Projekt geben.
    </p>
    <h5>Information zur Webseite</h5>
    <p>
      Diese Webseite dient lediglich dazu um die Events einfacher verwalten zu können.<br>
      Jegliche Kommunikation findet auf dem <router-link :to="{name: 'discord'}" target="_blank">Discord Server</router-link> statt.<br>
      Das heißt, das sich einige Informationen zum Umgang mit dem Event auf den <router-link :to="{name: 'discord'}" target="_blank">Discord Server</router-link>
      beziehen.
    </p>
    <h5>Events</h5>
    <p>
      Aktuelle und laufende Events werden in einen eigenen (<router-link :to="{name: 'discord'}" target="_blank">Discord Server</router-link>) Thread behandelt.<br>
      Für Diskussionen zu laufenden Events oder allgemein zum Hackathon nutzt bitte den <span class="text-white-50">#diskussion</span> Channel.<br>
      Um euer Projekt vorzustellen und alle auf den laufenden zu halten, könnt ihr nach der Teilnahmeanmeldung im Channel <span class="text-white-50">#teilnahmen</span>
      einen eigenen Thread erstellen.<br>
      In diesem Channel darf jeder nur 1 Thread erstellen und auch nur selbst in diesem schreiben! Zuwiderhandlungen werden kommentarlos gelöscht.<br>
    </p>
    <p>
      Eingereicht werden eure Projekte mittels einem git Repo via github / gitlab / bitbucket oder vergleichbar.<br>
      Dies muss spätestens zum Ablauf des Events (Stichzeit) mit einem dafür erstellten Tag erfolgen.<br>
      Wahl des Gewinners erfolgt über alle Teilnehmenden in einem Punkte/Kategorie Verfahren.<br>
      Es müssen jeweils bis zu 6 Punkte (0 bis 6, wobei 6 die beste Bepunktung ist) pro Kategorien<br>
    </p>
    <ul>
      <li>Kreativität (Wie kreativ ist die Idee)</li>
      <li>Umsetzung (Projektidee gut oder schlecht umgesetzt?)</li>
      <li>Aufbau (struktureller Aufbau von dem Projekt)</li>
      <li>Technik (Spagettiecode, Nutzung von Designpatterns und/oder Frameworks etc. pp.)</li>
      <li>Umfang (Projektumfang, Tests, Docs, zusätzliche Inhalte ...)</li>
    </ul>
    <p>
      und pro Projekt vergeben werden.<br>
      Zu bedenken ist, das die Punktewertung nicht zwingend die fachliche Qualität widerspiegelt, da ggf. bei der Bewertung der jeweilige Kenntnisstand des<br>
      Teilnehmers mit berücksichtigt wurde.<br>
    </p>
    <p>
      Anmeldung zu einem Event erfolgt mittels <span class="fa fa-thumbs-up"></span> Reaktion im entsprechenden Info Thread.<br>
      Dies muss vor Bekanntgabe vom Thema erfolgen.
    </p>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss">

</style>
