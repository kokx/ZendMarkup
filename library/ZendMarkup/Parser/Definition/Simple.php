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

class Simple implements DefinitionInterface
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
     * @param ParserInterface $parser
     * @param TreeNode $node
     * @param array $token
     *
     * @return TreeNode
     */
    public function __invoke(ParserInterface $parser, TreeNode $node, $token)
    {
        $node = new TreeNode(TreeNode::TYPE_MARKUP, $token[1], $this->name);

        while (null !== ($next = $parser->getNext())) {
            if ($this->isEndToken($token, $next)) {
                $node->setEndContent($next[1]);
                return $node;
            } else {
                $this->parseToken($parser, $node, $next);
            }
        }

        return $node;
    }

    /**
     * Parse a token.
     *
     * This provides a better extension point than the __invoke() method.
     *
     * @param ParserInterface $parser
     * @param TreeNode $node
     * @param array $next Token to be parsed
     *
     * @return void
     */
    public function parseToken(ParserInterface $parser, TreeNode $node, $next)
    {
        $node->addChild($parser->parseToken($next, $node));
    }

    /**
     * Check if the token is an end token.
     *
     * @param string $token Token of the current tag
     * @param string $next  Token we are parsing now
     *
     * @return boolean
     */
    public function isEndToken($token, $next)
    {
        $name = $token[2]['name'];
        return $next[0] == Lexer::TOKEN_TAG && ($next[1] == '[/' . $name . ']' || $next[1] == '[/]');
    }
}
