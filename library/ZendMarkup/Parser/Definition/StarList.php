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

/**
 * Named in the plural because list is a PHP keyword.
 */
class StarList extends Lists implements DefinitionInterface
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
    public function __invoke(ParserInterface $parser, TreeNode $node, $token)
    {
        $node = new TreeNode(TreeNode::TYPE_MARKUP, '', $this->name);

        // now parse the token again, so we make a list item
        $this->parseToken($parser, $node, $token);

        // we use a lookAhead here instead of getting the next token, because
        // if we end the tag, that token does not belong to this node in the
        // tree. It actually belongs to the next node.
        while (null !== ($next = $parser->lookAhead())) {
            if ($this->isEndToken($token, $next)) {
                return $node;
            } else {
                $next = $parser->getNext();
                $this->parseToken($parser, $node, $next);
            }
        }

        return $node;
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
        return $next[0] != Lexer::TOKEN_TAG || $next[1] != '[*]';
    }
}
