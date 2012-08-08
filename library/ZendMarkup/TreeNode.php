<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_ServiceManager
 */

namespace ZendMarkup;

class TreeNode
{

    /**
     * Children.
     *
     * @var array
     */
    protected $children;

    /**
     * Parent.
     *
     * @var TreeNode
     */
    protected $parent;

    /**
     * Content.
     *
     * @var string
     */
    protected $content;


    /**
     * Constructor
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Add a child.
     *
     * @param TreeNode $child
     */
    public function addChild(TreeNode $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    /**
     * Set a parent.
     *
     * @param TreeNode $parent
     */
    public function setParent(TreeNode $parent)
    {
        $this->parent = $parent;
    }
}
