<?php
/**
 * @license GPL-2.0
 *
 * Modified by learndash on 30-August-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\StellarWP\DB\QueryBuilder\Clauses;

use StellarWP\Learndash\StellarWP\DB\QueryBuilder\QueryBuilder;
use StellarWP\Learndash\StellarWP\DB\QueryBuilder\Types\JoinType;
use InvalidArgumentException;

/**
 * @since 1.0.0
 */
class Join {
	/**
	 * @var string
	 */
	public $table;

	/**
	 * @var string
	 */
	public $joinType;

	/**
	 * @var string|null
	 */
	public $alias;

	/**
	 * @param  string  $table
	 * @param  string  $joinType  \StellarWP\DB\QueryBuilder\Types\JoinType
	 * @param  string|null  $alias
	 */
	public function __construct( $joinType, $table, $alias = null ) {
		$this->table	= QueryBuilder::prefixTable( $table );
		$this->joinType = $this->getJoinType( $joinType );
		$this->alias	= trim( $alias );
	}

	/**
	 * @param  string  $type
	 *
	 * @return string
	 */
	private function getJoinType( $type ) {
		$type = strtoupper( $type );

		if ( array_key_exists( $type, JoinType::getTypes() ) ) {
			return $type;
		}

		throw new InvalidArgumentException(
			sprintf(
				'Join type %s is not supported. Please provide one of the supported join types (%s)',
				$type,
				implode( ',', JoinType::getTypes() )
			)
		);
	}
}
