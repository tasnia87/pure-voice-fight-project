<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 30-August-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace StellarWP\Learndash\StellarWP\Validation\Exceptions;

use Exception;
use StellarWP\Learndash\StellarWP\Validation\Exceptions\Contracts\ValidationExceptionInterface;

class ValidationException extends Exception implements ValidationExceptionInterface
{

}
