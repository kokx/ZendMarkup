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
class Lists extends Simple implements DefinitionInterface
{

    /**
     * Definition of the list item.
     *
     * @var DefinitionInterface
     */
    protected $listItemDefinition;

    /**
     * Constructor.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 'list';
        $this->listItemDefinition = new ListItem();
    }

    /**
     * Parse a token.
     *
     * This provides a better extension point than the __invoke() method.
     *
     * @param ParserInterface $parser
     * @param TreeNode $node
     * @param array $token Current token
     * @param array $next Token to be parsed
     *
     * @return void
     */
    public function parseToken(ParserInterface $parser, TreeNode $node, $next)
    {
        // we only parse ListItem tokens
        // otherwise we simply ignore
        if ($next[0] == Lexer::TOKEN_TAG) {
            $name = $next[2]['name'];
            if ($name == '*' || $name == 'li') {
                $func = $this->listItemDefinition;
                $node->addChild($func($parser, $node, $next));
            }
        }
    }
}
