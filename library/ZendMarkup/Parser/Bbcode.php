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

use ZendMarkup\Parser\Lexer\Bbcode as Lexer;
use ZendMarkup\TreeNode;

class Bbcode implements ParserInterface
{

    /**
     * Tokens.
     *
     * @var array
     */
    protected $tokens;

    /**
     * Last token number.
     *
     * @var int
     */
    protected $lastToken;

    /**
     * Parser definitions.
     *
     * @var array
     */
    protected $definitions = array();

    /**
     * Parser aliases.
     *
     * @todo implement this
     *
     * @var array
     */
    protected $aliases = array();


    /**
     * Parse the value
     *
     * @param string $value
     *
     * @return TreeNode
     */
    public function parse($value)
    {
        // first initialize the lexer
        $lexer = new Lexer();

        $this->tokens = $lexer->tokenize($value);
        $this->lastToken = 0;

        $tree = new TreeNode('');

        while (null !== ($token = $this->getNext())) {
            $tree->addChild($this->parseToken($token, $tree));
        }

        return $tree;
    }

    /**
     * Get the next token.
     *
     * @return array The requested token, or null if it doesn't exist
     */
    public function getNext()
    {
        if (isset($this->tokens[$this->lastToken])) {
            return $this->tokens[$this->lastToken++];
        }
        return null;
    }

    /**
     * Look one or more tokens ahead.
     *
     * @param string $num Number of tokens to look ahead, defaults to 1.
     *
     * @return array The requested token, or null if it doesn't exist
     */
    public function lookAhead($num = 1)
    {
        // first decrease the number by one, since we do a lookahead from the
        // last position, which we increased on the last call of getNext()
        $num--;

        if (isset($this->tokens[$this->lastToken + $num])) {
            return $this->tokens[$this->lastToken + $num];
        }

        return null;
    }

    /**
     * Parse a token.
     *
     * @return TreeNode
     */
    public function parseToken($token, $parent)
    {
        if ($token[0] == Lexer::TOKEN_TAG) {
            $name = $token[2]['name'];
            if (isset($this->definitions[$name])) {
                // call the function
                $func = $this->definitions[$name];
                return $func($this, $parent, $token);
            }
        }

        return new TreeNode($token[1]);
    }

    /**
     * Add a definition.
     *
     * @param string $name
     * @param string $definition
     *
     * @return void
     */
    public function addDefinition($name, $definition)
    {
        $this->definitions[$name] = $definition;
    }
}
