<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_ServiceManager
 */

namespace ZendMarkup\Parser;

use ZendMarkup\Tree;

interface ParserInterface
{

    /**
     * Parse the value
     *
     * @param string $value
     *
     * @return TreeNode
     */
    public function parse($value);

    /**
     * Get the next token.
     *
     * @return array
     */
    public function getNext();

    /**
     * Parse a token.
     *
     * @return TreeNode
     */
    public function parseToken($token, $parent);
}
