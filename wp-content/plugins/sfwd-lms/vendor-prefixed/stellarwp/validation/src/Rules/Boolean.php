<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by learndash on 30-August-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\StellarWP\Validation\Rules;

use Closure;
use StellarWP\Learndash\StellarWP\Validation\Contracts\Sanitizer;
use StellarWP\Learndash\StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Learndash\StellarWP\Validation\Contracts\ValidationRule;

class Boolean implements ValidationRule, ValidatesOnFrontEnd, Sanitizer
{
    /**
     * {@inheritDoc}
     *
     * @since 1.4.0
     */
    public static function id(): string
    {
        return 'boolean';
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.4.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.4.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            $fail(sprintf(__('%s must be an boolean', '%TEXTDOMAIN%'), '{field}'));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.4.0
     */
    public function serializeOption()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @since 1.4.0
     */
    public function sanitize($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
