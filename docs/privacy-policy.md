# Datenschutzerklärung – Account-Aktivitäts-Logging

Diese Erklärung beschreibt, wie diese Anwendung personenbezogene Daten im
Rahmen des Account-Aktivitäts-Loggings verarbeitet (DSGVO, insbesondere
Art. 5 Abs. 1 lit. e, Art. 32).

## 1. Verantwortlicher

Als Betreiber dieser Anwendung bestimmen Sie, welche Log-Daten erfasst
und wie lange sie aufbewahrt werden. Passen Sie die hier beschriebenen
Einstellungen an Ihre eigene Datenschutzerklärung an.

## 2. Zweck der Verarbeitung

Bei jeder API-Interaktion wird eine strukturierte Zeile in eine
separate Logdatei (`account-activity.log`) geschrieben. Zweck ist die
Sicherheit, Fehleranalyse und die Möglichkeit, verdächtige Zugriffe auf
Konten zu erkennen (Art. 6 Abs. 1 lit. f DSGVO).

## 3. Welche Daten erfasst werden

- Kontext einer bestehenden Interaktion: `accountId` / `accountUuid`,
  Route, HTTP-Methode, URI, Statuscode, Dauer, `correlation_id`
- Kennzeichen Gäste vs. angemeldete Konten: `guest`
- Client-Informationen:
  - `clientIdentHash` (nicht personenbezogener Fingerabdruck des Clients)
  - `userAgent` – **nur** als grober Typ (z. B. `browser`, `mobile`,
    `curl`, `postman`), nicht der vollständige User-Agent-String
  - `ip` – **maskiert** (letztes Oktett bzw. letzte Gruppe ersetzt)
- E-Mail-Adressen werden **niemals im Klartext** geloggt. Für
  unbekannte Adressen (z. B. fehlgeschlagene Login-Versuche) wird nur
  ein salted Hash (`emailHash`, HMAC-SHA256) verwendet.
- Klarnamen (z. B. der Anzeigename eines Kontos) werden **nicht**
  geloggt. Für die Zuordnung dienen ausschließlich die
  nicht-personenbezogenen Kennungen `accountId` / `accountUuid`.
  Dies gilt auch für Exception- und Fehlerkontexte: Klartext-E-Mail
  und Anzeigename werden zentral vor dem Schreiben entfernt bzw. durch
  `emailHash` / `accountUuid` ersetzt.

## 4. Aufbewahrungsdauer und Löschung

Datumsbasierte Log-Verzeichnisse werden nicht automatisch zur Laufzeit
gelöscht. Die Aufbewahrungsfrist wird über `logger.retention_days`
(Standard: 30 Tage) konfiguriert. Das Löschen erfolgt über das Kommando:

```bash
./bin/hackathon logs prune
```

Dieses entfernt alle Log-Verzeichnisse, die älter als die konfigurierte
Frist sind. Die Ausführung per Cron-Planer liegt in Ihrer Verantwortung.

## 5. Datenminimierung und technische Maßnahmen

- Kennzeichnung aller Klartext-identifizierbaren Werte vermieden.
- IP-Adressen und User-Agents werden vor dem Schreiben vergröbert.
- E-Mail-Adressen werden pseudonymisiert (salted Hash) verarbeitet.

---

# Privacy Policy – Account Activity Logging

This policy describes how this application processes personal data in the
context of account activity logging (GDPR, in particular Art. 5(1)(e),
Art. 32).

## 1. Controller

As the operator of this application you decide which log data is captured
and how long it is retained. Adjust the settings described here to your
own privacy policy.

## 2. Purpose of processing

For every API interaction a structured line is written to a separate log
file (`account-activity.log`). The purpose is security, error analysis and
the ability to detect suspicious access to accounts (Art. 6(1)(f) GDPR).

## 3. Data captured

- Context of an existing interaction: `accountId` / `accountUuid`,
  route, HTTP method, URI, status code, duration, `correlation_id`
- Indicator guest vs. authenticated account: `guest`
- Client information:
  - `clientIdentHash` (non-personal client fingerprint)
  - `userAgent` – **only** as a coarse type (e.g. `browser`, `mobile`,
    `curl`, `postman`), not the full user-agent string
  - `ip` – **masked** (last octet / last group replaced)
- Email addresses are **never** logged in plain text. For unknown
  addresses (e.g. failed login attempts) only a salted hash
  (`emailHash`, HMAC-SHA256) is used.
- Display names (e.g. an account's name) are **not** logged. For
  attribution only the non-personal identifiers `accountId` /
  `accountUuid` are used. This also applies to exception and error
  contexts: plain-text email and display name are removed centrally
  before writing or replaced with `emailHash` / `accountUuid`.

## 4. Retention and deletion

Dated log directories are not deleted automatically at runtime. The
retention period is configured via `logger.retention_days` (default: 30
days). Deletion is performed with the command:

```bash
./bin/hackathon logs prune
```

This removes all log directories older than the configured period.
Scheduling the deletion via a cron job is your responsibility.

## 5. Data minimisation and technical measures

- Plain-text identifiers are avoided.
- IP addresses and user agents are coarse-grained before writing.
- Email addresses are processed pseudonymised (salted hash).
