<?php declare(strict_types=1);

namespace ownHackathon\Core\SharedKernel\Domain\Message;

/**
 * Sammlung aller strukturierten Log-Nachrichten.
 *
 * Konventionen für den PSR-3-Kontext:
 * - Entity-Bezug als camelCase-Schlüssel, z. B. `{entity}Id` (accountId, workspaceId, eventId, tokenId).
 * - Persistenz-Ausfälle gefiltert über einen `exception`-Schlüssel.
 * - Fehlerhafte Visibility-Werte über `visibility` plus `{entity}Id`.
 * - HTTP-/Auth-Kontext über `uri`, `ip`, `status`, `method`, `correlation_id`.
 * - Keine sensiblen Daten (Passwörter, Tokens, Sitzungsgeheimnisse) in Nachricht oder Kontext ablegen.
 */
interface LogMessage
{
    public const string UNAUTHORIZED_ACCESS = 'Route was called without authentication';
}
