<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2022 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\array_key_exists_;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Psy\Exception\FatalErrorException;

/**
 * Code cleaner pass to ensure we only allow variables, array fetch and property
 * fetch expressions in array_key_exists() calls.
 */
class array_key_existsPass extends CodeCleanerPass
{
    const EXCEPTION_MSG = 'Cannot use array_key_exists() on the result of an expression (you can use "null !== expression" instead)';

    /**
     * @throws FatalErrorException
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        if (!$node instanceof array_key_exists_) {
            return;
        }

        foreach ($node->vars as $var) {
            if (!$var instanceof Variable && !$var instanceof ArrayDimFetch && !$var instanceof PropertyFetch && !$var instanceof NullsafePropertyFetch) {
                throw new FatalErrorException(self::EXCEPTION_MSG, 0, \E_ERROR, null, $node->getLine());
            }
        }
    }
}
