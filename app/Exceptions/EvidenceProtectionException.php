<?php

namespace App\Exceptions;

use DomainException;

/**
 * Thrown when an action would break an evidence-integrity rule (editing or deleting
 * audit entries, altering saved media, changing a closed case, ...).
 * Pages catch this type only, so genuine programming errors are never disguised.
 */
class EvidenceProtectionException extends DomainException
{
}
