<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_ServiceManager
 */

namespace ZendMarkup\Parser\Lexer;

class Bbcode implements LexerInterface
{

    // tokens
    const TOKEN_TEXT = 'text';
    const TOKEN_TAG = 'tag';
    const TOKEN_NEWLINE = 'newline'; // TODO: implement this in the code

    protected $_regexes = array(
        'text' => '.*?',
        'tag_start' => '\[',
        'tag_end' => '\]',
        'name' => '[^\[\]=\s]+',
        'attr_operator' => '=',
        'newline' => "\n",
        'whitespace' => '\s',
        'value' => '[^\'\"\]\s]+',
        'squote' => "'",
        'dquote' => '"',
        'value_squote' => "[^']*",
        'value_dquote' => '[^"]*',
        'closing' => '\?'
    );

    /**
     * Tokenize the string
     *
     * The token array is an array with for every token, a sub array:
     * array($type, $content [, $data ])
     *
     * The $type contains the type of token.
     * The $content contains the content of the token.
     * The $data (optional) contains extra info on the token.
     *
     * @param string $value
     *
     * @return array
     *
     * @todo make this charset-aware
     */
    public function tokenize($value)
    {
        // canonicalize newlines
        $value = str_replace(array("\r\n", "\r"), "\n", $value);

        $tokens = array();
        $ptr = 0;
        $len = strlen($value);

        // the state machine


        // the all mighty regex for a complete tag

        $valRegex = "(" . $this->_regexes['squote'] . $this->_regexes['value_squote'] . $this->_regexes['squote']
                  . "|" . $this->_regexes['dquote'] . $this->_regexes['value_dquote'] . $this->_regexes['dquote']
                  . "|" . $this->_regexes['value'] . ")";

        $valueRegex = "(?<eq>" . $this->_regexes['attr_operator'] . ")(?<val>" . $valRegex . ")";
        $attrRegex = "(" . $this->_regexes['whitespace'] . $this->_regexes['name'] . $this->_regexes['attr_operator'] . $valRegex . ")";

        $regex = "#\G(?<text>" . $this->_regexes['text'] . ")(?<tag>"
            . "(?<open>" . $this->_regexes['tag_start'] . ")"
            . "(?<closing>" . $this->_regexes['closing'] . ")?"
            . "(?<name>" . $this->_regexes['name'] . ")(?<value>" . $valueRegex . ")?"
            . "(?<attrs>" . $attrRegex . "*)" . "(?<end>" . $this->_regexes['tag_end'] . ")|\n)#s";

        while ($ptr < $len) {
            $matches = array();
            if (!preg_match($regex, $value, $matches, null, $ptr)) {
                // there is only text from here
                $tokens[] = array(self::TOKEN_TEXT, substr($value, $ptr));
                $ptr = $len;
                break;
            }
            $ptr += strlen($matches[0]);

            // first add text
            if (!empty($matches['text'])) {
                $tokens[] = array(self::TOKEN_TEXT, $matches['text']);
            }

            // now the tag
            if (!empty($matches['tag'])) {
                if ($matches['tag'] == "\n") {
                    $tokens[] = array(self::TOKEN_NEWLINE, $matches['tag']);
                } else {
                    // add the tag and find its tag info
                    $tokens[] = array(self::TOKEN_TAG, $matches['tag'], $this->findTagInfo($matches['tag']));
                }
            }
        }

        return $tokens;
    }

    /**
     * Find the info of the tag
     *
     * @param string $tag
     *
     * @return array
     */
    protected function findTagInfo($tag)
    {
        // remove the '[' and ']' from the tag
        $tag = substr($tag, 1, -1);
        $ptr = 0;

        $info = array(
            'name' => '',
            'attributes' => array()
        );

        // regex to be used for the value
        $valRegex = "(" . $this->_regexes['squote'] . "(?<sval>" . $this->_regexes['value_squote'] . ")" . $this->_regexes['squote']
                  . "|" . $this->_regexes['dquote'] . "(?<dval>" . $this->_regexes['value_dquote'] . ")" . $this->_regexes['dquote']
                  . "|(?<val>" . $this->_regexes['value'] . "))";

        // first, read the name
        $matches = array();
        preg_match('#^' . $this->_regexes['name'] . '#', $tag, $matches);
        $ptr += strlen($matches[0]);

        $info['name'] = $matches[0];

        // see if we should continue with the value for the name attribute,
        // or that we are finished with the tag
        if (empty($tag[$ptr])) {
            return $info;
        } elseif ($tag[$ptr] == '=') {
            $ptr++;
            // read the value
            $matches = array();
            preg_match('#\G' . $valRegex . '#s', $tag, $matches, null, $ptr);
            $ptr += strlen($matches[0]);

            $val = '';
            if (!empty($matches['val'])) {
                $val = $matches['val'];
            } elseif (!empty($matches['dval'])) {
                $val = $matches['dval'];
            } elseif (!empty($matches['sval'])) {
                $val = $matches['sval'];
            }
            $info['attributes'][$info['name']] = $val;
        }

        $attrRegex = $this->_regexes['whitespace'] . '(?<name>' . $this->_regexes['name'] . ')'
                   . '(?<eq>' . $this->_regexes['attr_operator'] . ')'
                   . '(?<value>' . $valRegex . ')';

        $matches = array();
        while (preg_match('#' . $attrRegex . '#', $tag, $matches, null, $ptr)) {
            $ptr += strlen($matches[0]);

            // get the name and value and add as info
            $name = $matches['name'];

            $val = '';
            if (!empty($matches['val'])) {
                $val = $matches['val'];
            } elseif (!empty($matches['dval'])) {
                $val = $matches['dval'];
            } elseif (!empty($matches['sval'])) {
                $val = $matches['sval'];
            }

            $info['attributes'][$name] = $val;

            $matches = array();
        }

        return $info;
    }
}
