<?php

namespace App\Foundation;

/**
 * 代码基于 https://github.com/whiteCcinn/tire-php 修改而来
 *
 * 修复了首个敏感字连接在一起无法查出敏感词的bug
 * 修复了还原字符串时未能正确处理ascii字符的bug
 */
class Tire
{
    public $tree = [];
    public $indexCode = [];
    private $statistics = false;

    public function add(string $word)
    {
        $tree = &$this->tree;

        foreach ($this->split($word) as $node) {
            $node = $this->utf8TransformAscii($node);
            $tree = &$this->insertNode($tree, $node);
        }
        $tree['end'] = true;

        return $this;
    }

    private function &insertNode(&$tree, $node)
    {
        if (isset($tree[$node])) {
            return $tree[$node];
        }

        $tree[$node] = [];
        return $tree[$node];
    }

    public function seek(string $text, $statistics = false, $first = true)
    {
        $match = [];
        $this->statistics = $statistics;
        $tree = &$this->tree;

        foreach ($this->split($text) as $k => $word) {
            if (!$first && !$k) {
                continue;
            }
            $code = $this->utf8TransformAscii($word);
            $tree = &$this->beginFind($tree, $code, $sensitive);
            if (isset($tree['end'])) {
                // 匹配到了词
                !$this->exist($sensitive, $statistics) && $match[] = $this->asciiTransformUtf8($sensitive);
            }
        }

        foreach ($match as $words) {
            $match = array_merge($match, $this->seek($words, $statistics, false));
        }
        return $match;
    }

    private function &beginFind(&$tree, $node, &$prefix = '')
    {
        if (isset($tree[$node])) {
            $prefix = $prefix . "\u{$node}";
            return $tree[$node];
        }

        // fixed
        if (isset($this->tree[$node])) {
            $prefix = "\u{$node}";
            return $this->tree[$node];
        }

        $prefix = '';
        return $this->tree;
    }

    public function statistics()
    {
        if (!$this->statistics) {
            return false;
        }
        $that      = $this;
        $indexCode = array();
        array_walk($this->indexCode, function ($statistics, &$sensitive) use ($that, &$indexCode) {
            $sensitive               = $that->asciiTransformUtf8($sensitive);
            $indexCode[ $sensitive ] = $statistics;
        });
        return $indexCode;
    }

    private function exist($sensitive, $statistics = false)
    {
        if (isset($this->indexCode[ $sensitive ])) {
            $statistics && $this->indexCode[ $sensitive ]++;
            return true;
        } else {
            $this->indexCode[ $sensitive ] = 1;
            return false;
        }
    }

    /**
     * 单字符转换编码
     *
     * @param $utf8_str
     * @return string
     */
    public function utf8TransformAscii($utf8_str)
    {
        if (ord($utf8_str) <= 127) {
            return ord($utf8_str);
        }

        $ascii = (ord(@$utf8_str[0]) & 0xF) << 12;
        $ascii |= (ord(@$utf8_str[1]) & 0x3F) << 6;
        $ascii |= (ord(@$utf8_str[2]) & 0x3F);

        return $ascii;
    }

    /**
     * 编码转单字符
     *
     * @param $ascii
     * @return string
     */
    public function asciiTransformUtf8($ascii)
    {
        if (strpos($ascii, '\u') !== false) {
            $asciis = explode('\u', $ascii);
            array_shift($asciis);
        } else {
            $asciis = array($ascii);
        }

        $utf8_str = '';
        foreach ($asciis as $ascii) {
            $ascii = (int) $ascii;
            // fixed
            if ($ascii <= 127) {
                $utf8_str .= chr($ascii);
                continue;
            }
            $ord_1 = 0xe0 | ($ascii >> 12);
            $ord_2 = 0x80 | (($ascii >> 6) & 0x3f);
            $ord_3 = 0x80 | ($ascii & 0x3f);
            $utf8_str .= chr($ord_1) . chr($ord_2) . chr($ord_3);
        }

        return $utf8_str;
    }

    /**
     * utf8拆字
     *
     * @param string $str
     * @return \Generator
     */
    private function split(string $str)
    {
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = $str[$i];
            $n = ord($c);
            if (($n >> 7) == 0) {
                //0xxx xxxx, asci, single
                yield $c;
            } elseif (($n >> 4) == 15) { //1111 xxxx, first in four char
                if ($i < $len - 3) {
                    yield $c . $str[ $i + 1 ] . $str[ $i + 2 ] . $str[ $i + 3 ];
                    $i += 3;
                }
            } elseif (($n >> 5) == 7) {
                //111x xxxx, first in three char
                if ($i < $len - 2) {
                    yield $c . $str[ $i + 1 ] . $str[ $i + 2 ];
                    $i += 2;
                }
            } elseif (($n >> 6) == 3) {
                //11xx xxxx, first in two char
                if ($i < $len - 1) {
                    yield $c . $str[ $i + 1 ];
                    $i++;
                }
            }
        }
    }
}
