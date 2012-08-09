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
class ListItem extends Simple implements DefinitionInterface
{

    /**
     * Constructor.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 'list-item';
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
        return parent::isEndToken($token, $next) || $next[0] == Lexer::TOKEN_NEWLINE;
    }
}
