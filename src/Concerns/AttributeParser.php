<?php namespace Dietercoopman\Smart\Concerns;

class AttributeParser
{

    public function getAttributes($htmlortag, $name = false)
    {
        $p            = 0;
        $tag          = false;
        $inquote      = false;
        $started      = false;
        $stack        = '';
        $attrState    = -1;    // -1:NOTHING   1:NAME 2:VALUE
        $currentAttr  = false;
        $attrValuePos = -1;
        $attr         = array();
        while ($p < strlen($htmlortag)) {
            $c = substr($htmlortag, $p, 1);

            if ($c == ' ' && $started && !$tag) {
                $tag   = $stack;
                $stack = '';

            } else if ($started && $c == '>' && ($attrState != 2 || $inquote == ' ')) {        // END OF TAG (if not in a value, doesn't work without braces)
                $started = false;
                if ($attrState == 1 && trim($stack) != '/')
                    $attr[trim($stack)] = true;
                if ($attrState == 2)
                    $attr[$currentAttr] = $stack;
                break;    // DONE

            } else if ($started && $tag && $c == '=' && $attrState != 2) {                    // END OF ATTR NAME, BEGIN OF VALUE
                $currentAttr = trim($stack);
                $stack       = '';
                $attrState   = 2;

            } else if ($started && $tag && $c == ' ' && $attrState == 1) {                    // END OF ATTR NAME, BEGIN OF VALUE
                $currentAttr = trim($stack);
                $stack       = '';
                $attrState   = 5;

            } else if ($started && $tag && $attrState == 5) {                                // CHAR AFTER SPACE AFTER ATTR NAME, BEGIN OF ANOTHER ATTR
                $attr[$currentAttr] = true;
                $currentAttr        = false;
                $stack              .= $c;
                $attrState          = 1;

            } else if (!$started && $c == '<') {                                            // BEGIN OF TAG
                $started = true;

            } else if ($started && $tag && $attrState == 2 && $c === $inquote) {            // END OF VALUE
                $attr[$currentAttr] = $stack;
                $stack              = '';
                $attrState          = -1;
                $inquote            = false;
                $attrValuePos       = -1;

            } else if ($started && $tag && $attrState == 2 && $attrValuePos == -1) {        // MIDDLE OF VALUE
                $attrValuePos = 0;
                if ($c == '\'') $inquote = '\'';
                else if ($c == '"') $inquote = '"';
                else {
                    $inquote      = ' ';
                    $stack        .= $c;
                    $attrValuePos = 1;
                }

            } else if ($started && $tag && $attrState == -1) {                            // BEGIN OF ATTR NAME
                $attrState = 1;
                $stack     .= $c;

            } else {
                $stack .= $c;
                if ($attrState == 2) $attrValuePos++;

            }
            $p++;
        }
        return $name ? $attr[$name] : $attr;
    }

}
