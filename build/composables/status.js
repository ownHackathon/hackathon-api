function getStatusText(value) {
  const statusList = ['bald', 'vorbereiten', 'läuft', 'auswerten', 'beendet', 'geschlossen',];
  return statusList[value - 1];
}

export {
  getStatusText,
};
