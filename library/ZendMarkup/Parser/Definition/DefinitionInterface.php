<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_ServiceManager
 */

namespace ZendMarkup\Parser\Definition;

use ZendMarkup\Parser\ParserInterface;
use ZendMarkup\TreeNode;

interface DefinitionInterface
{

    /**
     * Invoke the definition.
     *
     * @param ParserInterface $parser
     * @param TreeNode $node
     * @param array $token
     *
     * @return TreeNode
     */
    public function __invoke(ParserInterface $parser, TreeNode $node, $token);
}
