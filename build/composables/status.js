function getStatusText(value) {
  const statusList = ['bald', 'vorbereiten', 'läuft', 'auswerten', 'beendet', 'geschlossen', 'abgebrochen', 'versteckt'];
  return statusList[value - 1];
}

export {
  getStatusText,
};
