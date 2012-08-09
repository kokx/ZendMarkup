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

    // Types
    const TYPE_TEXT = 'text';
    const TYPE_MARKUP = 'markup';


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
     * Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Content.
     *
     * @var string
     */
    protected $content = '';

    /**
     * Content that comes after the children.
     *
     * @var string
     */
    protected $endContent = '';


    /**
     * Constructor
     *
     * @param string $content
     */
    public function __construct($type, $content, $name = '')
    {
        $this->type = $type;
        $this->content = $content;
        $this->name = $name;
    }

    /**
     * Add an attribute.
     *
     * @param string $name
     * @param string $value
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Add multiple attributes.
     *
     * @param array $attributes
     */
    public function addAttributes($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->addAttribute($name, $value);
        }
    }

    /**
     * Set the end content.
     *
     * @param string $endContent
     */
    public function setEndContent($endContent)
    {
        $this->endContent = $endContent;
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
