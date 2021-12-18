<?php

namespace Dietercoopman\Smart\Concerns;

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
        $attr         = [];
        while ($p < strlen($htmlortag)) {
            $c = substr($htmlortag, $p, 1);

            if ($c == ' ' && $started && !$tag) {
                $tag   = $stack;
                $stack = '';
            } elseif ($started && $c == '>' && ($attrState != 2 || $inquote == ' ')) {        // END OF TAG (if not in a value, doesn't work without braces)
                $started = false;
                if ($attrState == 1 && trim($stack) != '/') {
                    $attr[trim($stack)] = true;
                }
                if ($attrState == 2) {
                    $attr[$currentAttr] = $stack;
                }

                break;    // DONE
            } elseif ($started && $tag && $c == '=' && $attrState != 2) {                    // END OF ATTR NAME, BEGIN OF VALUE
                $currentAttr = trim($stack);
                $stack       = '';
                $attrState   = 2;
            } elseif ($started && $tag && $c == ' ' && $attrState == 1) {                    // END OF ATTR NAME, BEGIN OF VALUE
                $currentAttr = trim($stack);
                $stack       = '';
                $attrState   = 5;
            } elseif ($started && $tag && $attrState == 5) {                                // CHAR AFTER SPACE AFTER ATTR NAME, BEGIN OF ANOTHER ATTR
                $attr[$currentAttr] = true;
                $currentAttr        = false;
                $stack              .= $c;
                $attrState          = 1;
            } elseif (!$started && $c == '<') {                                            // BEGIN OF TAG
                $started = true;
            } elseif ($started && $tag && $attrState == 2 && $c === $inquote) {            // END OF VALUE
                $attr[$currentAttr] = $stack;
                $stack              = '';
                $attrState          = -1;
                $inquote            = false;
                $attrValuePos       = -1;
            } elseif ($started && $tag && $attrState == 2 && $attrValuePos == -1) {        // MIDDLE OF VALUE
                $attrValuePos = 0;
                if ($c == '\'') {
                    $inquote = '\'';
                } elseif ($c == '"') {
                    $inquote = '"';
                } else {
                    $inquote      = ' ';
                    $stack        .= $c;
                    $attrValuePos = 1;
                }
            } elseif ($started && $tag && $attrState == -1) {                            // BEGIN OF ATTR NAME
                $attrState = 1;
                $stack     .= $c;
            } else {
                $stack .= $c;
                if ($attrState == 2) {
                    $attrValuePos++;
                }
            }
            $p++;
        }

        return $name ? $attr[$name] : $attr;
    }

    public function rebuild($attributes)
    {
        unset($attributes['src']);
        unset($attributes['smart']);
        unset($attributes['data-src']);
        unset($attributes['data-template']);
        unset($attributes['data-background']);
        unset($attributes['data-disk']);

        $attributesString = "";
        foreach ($attributes as $key => $value) {
            if (!blank($key))
                $attributesString .= $key . "='" . $value . "' ";
        }

        return $attributesString;
    }
}
