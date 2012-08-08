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

use ZendMarkup\TreeNode;
use ZendMarkup\Parser\Lexer\Bbcode as Lexer;
use ZendMarkup\Parser\ParserInterface;
use ZendMarkup\Parser\Definition\DefinitionInterface;

class SimpleTag
{

    /**
     * Name of the tag.
     *
     * @var string
     */
    protected $name;


    /**
     * Constructor.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Invoke the definition.
     *
     * @param TreeNode $node
     *
     * @return TreeNode
     */
    public function __invoke(ParserInterface $parser, TreeNode $node, $token)
    {
        $node = new TreeNode($token[1]);

        while (null !== ($next = $parser->getNext())) {
            if ($next[0] == Lexer::TOKEN_TAG && $next[1] == '[/b]') {
                return $node;
            } else {
                $node->addChild($parser->parseToken($next, $node));
            }
        }

        return $node;
    }
}
