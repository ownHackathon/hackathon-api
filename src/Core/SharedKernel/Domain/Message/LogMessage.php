<?php declare(strict_types=1);

namespace Core\SharedKernel\Domain\Message;

/**
 * Sammlung aller strukturierten Log-Nachrichten.
 *
 * Konventionen für den PSR-3-Kontext:
 * - Entity-Bezug als camelCase-Schlüssel, z. B. `{entity}Id` (accountId, workspaceId, eventId, tokenId).
 * - Persistenz-Ausfälle gefiltert über einen `exception`-Schlüssel.
 * - Fehlerhafte Visibility-Werte über `visibility` plus `{entity}Id`.
 * - HTTP-/Auth-Kontext über `uri`, `ip`, `status`, `method`, `correlation_id`.
 * - Keine sensiblen Daten (Passwörter, Tokens, Sitzungsgeheimnisse) in Nachricht oder Kontext ablegen.
 *
 * DSGVO-Konventionen für den Account-Aktivitäts-Kontext:
 * - Keine E-Mail-Adresse im Klartext; für bestehende Accounts accountId/accountUuid verwenden.
 * - Für unbekannte Adressen ausschließlich `emailHash` (salted), nie die Adresse selbst.
 * - `ip` nur maskiert (letztes Oktett), `userAgent` nur als grobes Kurzmerkmal ablegen.
 */
interface LogMessage
{
    public const string UNAUTHORIZED_ACCESS = 'Route was called without authentication';
}
