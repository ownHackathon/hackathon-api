function getStatusText(value) {
  const statusList = ['bald', 'vorbereiten', 'läuft', 'auswerten', 'beendet', 'geschlossen', 'versteckt'];
  return statusList[value - 1];
}

export {
  getStatusText,
};
