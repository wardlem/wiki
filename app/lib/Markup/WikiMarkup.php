<?php

namespace Markup;
use cebe\markdown\Markdown;

class WikiMarkup extends Markdown
{
    public $html5 = true;
    protected $internalLinkExistsCall = null;
    protected $createInternalLinkCall = null;

    protected function identifyLine($lines, $current)
    {
        $line = $lines[$current];
        switch($line[0]){
            case '*':
                if (isset($line[1]) && $line[1] == ' ') {
                    return 'unorderedList';
                }
                break; // Not a list tag
            case '#':
                if (isset($line[1]) && $line[1] == ' ') {
                    return 'orderedList';
                } else if (strpos($line, '###') === 0){
                    return 'pre';
                }
                break; // Not a list tag
            case '=':
                return 'headline';
            case '-':
                if (strpos($line, '----') === 0) {
                    return 'horizontalRule';
                }
                break; // Not an hr tag
            case '`':   // Use Github style code block
                if (strpos($line, '```') === 0) {
                    return 'fencedCode';
                }
                break;
            case '|':
                return 'table';
            case '/':
                if (strpos($line, '/* ') === 0){
                    return 'comment';
                } else if (rtrim($line) === '//'){
                    return 'lineBreak';
                }
                break;
            case '>':
                if (isset($line[1]) && $line[1] == ' '){
                    return 'quote';
                } else if (strpos($line, '>>>') === 0){
                    return 'panel';
                }
                break;
            case '{':
                if (rtrim($line) === '{{{'){
                    return 'noFormat';
                }
                break;
            case '&':
                if (strpos($line, '&&&') === 0){
                    return 'escapeHtml';
                }
                break;

        }

        return 'paragraph';
    }

    protected function inlineMarkers()
    {
        $markers = [
            '**'        => 'parseStrong',
            '//'        => 'parseEmphasis',
            '[['        => 'parseLink',
            '\\\\'      => 'parseNewline',
            '{{{'       => 'parseNoFormat',
            '##'        => 'parseMonospace',
            '^^'        => 'parseSuperscript',
            ',,'        => 'parseSubscript',
            '__'        => 'parseUnderline',
            '{{'        => 'parseAsset',
            '~'         => 'parseEscape',
            '`'         => 'parseCode',
            'http://'   => 'parseImpliedLink',
            'https://'  => 'parseImpliedLink'

        ];

        return $markers;
    }

    public function consumeParagraph($lines, $current)
    {
        $block = [
            'type' => 'paragraph',
            'content' => [],
        ];

        for ($i = $current, $count = count($lines); $i < $count; $i++) {
            if (ltrim($lines[$i]) === ''){
                return [$block, $i];
            } else if ($this->identifyLine($lines, $i) !== 'paragraph') {
                return [$block, --$i];
            } else {
                $block['content'][] = $lines[$i];
            }
        }

        return [$block, $i];
    }

    protected function consumePre($lines, $current)
    {
        // consume until ###
        $block = [
            'type' => 'pre',
            'content' => [],
        ];
        for($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== '###') {
                $block['content'][] = $line;
            } else {
                break;
            }
        }
        return [$block, $i];
    }

    protected function consumeEscapeHTML($lines, $current)
    {
        // consume until &&&
        $block = [
            'type' => 'escapeHTML',
            'content' => [],
        ];
        for($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== '&&&') {
                $block['content'][] = $line;
            } else {
                break;
            }
        }
        return [$block, $i];
    }

    protected function consumePanel($lines, $current)
    {
        // consume until >>>
        $block = [
            'type' => 'panel',
            'content' => [],
        ];
        $line = rtrim($lines[$current]);
        $fence = substr($line, 0, $pos = strrpos($line, '`') + 1);
        $extra = substr($line, $pos);
        if (!empty($extra)) {
            $block['extra'] = $extra;
        }
        for($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== '>>>') {
                $block['content'][] = $line;
            } else {
                break;
            }
        }
        return [$block, $i];
    }

    protected function consumeLineBreak($lines, $current)
    {
        return [['type' => 'lineBreak'], $current];
    }

    protected function consumeFencedCode($lines, $current)
    {
        // consume until ```
        $block = [
            'type' => 'code',
            'content' => [],
        ];
        $line = rtrim($lines[$current]);
        $fence = substr($line, 0, $pos = strrpos($line, '`') + 1);
        $language = substr($line, $pos);
        if (!empty($language)) {
            $block['language'] = $language;
        }
        for($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== $fence) {
                $block['content'][] = $line;
            } else {
                break;
            }
        }
        return [$block, $i];
    }

    protected function consumeOrderedList($lines, $current)
    {
        return $this->consumeList($lines, $current, 'ol');
    }

    protected function consumeUnorderedList($lines, $current)
    {
        return $this->consumeList($lines, $current, 'ul');
    }

    protected function consumeList($lines, $current, $type, $notUsed = null)
    {
        $block = [
            'type' => 'list',
            'list' => $type,
            'items' => [],
        ];

        $char = $type === 'ol' ? '#' : '*';

        for ($i = $current, $count = count($lines); $i < $count; $i++){
            $line = $lines[$i];
            if (isset($line[0]) && $line[0] === $char){
                if (!isset($line[1])){
                    $block['items'][] = '';
                } else {
                    switch($line[1]){
                        case ' ':
                            $block['items'][] = substr($line, 2);
                            break;
                        case '#':
                        case '*':
                            $subChar = $line[1];
                            $subList = [];
                            for (; $i < $count; $i++){
                                $subLine = substr($lines[$i], 1);
                                if (isset($subLine[0]) && $subLine[0] === $subChar){
                                    $subList[] = $subLine;
                                } else {
                                    break;
                                }
                            }
                            --$i;
                            $block['items'][] = $this->consumeList($subList, 0, $line[1] === '#' ? 'ol' : 'ul')[0];
                            break;
                        default:
                            break 2;
                    }
                }

            } else {
                break;
            }
        }

        return [$block, --$i];
    }

    protected function consumeHeadline($lines, $current)
    {
        $line = $lines[$current];
        $level = 1;
        while (isset($line[$level]) && $line[$level] === '='){
            $level++;
        }
        $block = [
            'type' => 'headline',
            'content' => trim($line, '='),
            'level' => $level,
        ];

        return [$block, $current];

    }

    protected function consumeHorizontalRule($lines, $current)
    {
        $block = [
            'type' => 'hr',
        ];
        return [$block, $current];
    }

    protected function consumeTable($lines, $current)
    {
        $block = [
            'type' => 'table',
            'rows' => [],
        ];
        for ($i = $current, $count = count($lines); $i < $count; $i++){
            $line = trim($lines[$i], '|');
            if ($line === ''){
                break;
            }
            $block['rows'][] = $line;
        }

        return [$block, $i];
    }

    protected function consumeComment($lines, $current)
    {
        $block = [
            'type' => 'comment'
        ];

        return [$block, $current];
    }

    protected function consumeNoFormat($lines, $current)
    {

        $block = [
            'type' => 'noFormat',
            'lines' => [],
        ];

        for ($i = $current + 1, $count = count($lines); $i < $count; $i++){
            $line = $lines[$i];
            if (rtrim($line) === '}}}'){
                return [$block, $i];
            } else {
                $block['lines'][] = $line;
            }
        }

        return $block;

    }

    protected function renderNoFormat($block)
    {
        return empty($block['lines']) ? '' : "<p>\n" . implode("\n", $block['lines']) ."</p>\n";
    }

    protected function renderParagraph($block)
    {
        return empty($block['content']) ? '' : parent::renderParagraph($block);
    }

    protected function renderLineBreak($block)
    {
        return '<br>';
    }

    protected function renderFencedCode($block)
    {
        return "<code>\n" . implode("\n", $block['content']) . "\n</code>";
    }

    protected function renderList($block)
    {
        $tag = $block['list'];

        //$output = '<pre>' . print_r($block['items'], true) . '</pre>';

        $output = "<" . $tag . ">\n";
        $items = $block['items'];
        for ($i = 0, $count = count($items); $i < $count; $i++){
            if (!is_array($items[$i])){
                $output .= "<li>\n";
                $output .= $this->parseInline($items[$i]);

                if (isset($items[$i + 1]) && is_array($items[$i + 1]) && isset($items[$i + 1]['list'])){
                    $output .= $this->renderList($items[$i + 1]);
                    $i++;
                }
                $output .= "</li>\n";
            }

        }
        $output .= "</" . $tag . ">\n";

        return $output;
    }

    protected function renderTable($block)
    {
        $output = "<table>\n<tbody>\n";
        foreach($block['rows'] as $row){
            $row = explode('|', $row);
            $output .= "<tr>\n";
            foreach ($row as $cell){
                if (isset($cell[0]) && $cell[0] === '='){
                    $output .= "<th>" . trim($this->parseInline(substr($cell, 1))) . "</th>\n";
                } else {
                    $output .= "<td>" . trim($this->parseInline($cell)) . "</td>\n";
                }
            }
            $output .= "</tr>\n";
        }
        $output .= "</tbody>\n</table>\n";

        return $output;
    }

    protected function renderComment($block)
    {
        return '';
    }

    protected function renderCode($block)
    {
        $class = isset($block['language']) ? ' class="language-' . $block['language'] . '"' : '';
        return "<code$class>" . htmlspecialchars(implode("\n", $block['content']) . "\n", ENT_NOQUOTES, 'UTF-8') . '</code>';
    }

    protected function renderPre($block)
    {
        return "<pre class='monospace'>" . htmlspecialchars(implode("\n", $block['content']) . "\n", ENT_NOQUOTES, 'UTF-8') . '</pre>';
    }

    protected function renderPanel($block)
    {
        $extra = isset($block['extra']) ? ' ' . $block['extra'] : '';
        return "<div class='panel$extra'>\n" . $this->parseBlocks($block['content']) . "\n</div>";
    }

    protected function renderEscapeHTML($block)
    {
        return "<div>\n" . htmlspecialchars($this->parseBlocks($block['content']), ENT_NOQUOTES, "UTF-8") . "</div>\n";
    }

    protected function parseStrong($text)
    {
        if (preg_match('/^[*]{2}(.*?)[*]{2}/', $text, $matches)){
            return ['<strong>' . $this->parseInline($matches[1]) . '</strong>', strlen($matches[0])];
        }
        return ['**', 2];
    }

    protected function parseEmphasis($text)
    {
        if (preg_match('/^[\/]{2}(.*?)[\/]{2}/', $text, $matches)){
            return ['<em>' . $this->parseInline($matches[1]) . '</em>', strlen($matches[0])];
        }
        return ['//', 2];
    }

    protected function parseLink($text)
    {
        if (preg_match('/^[\[]{2}(.+?)[\]]{2}/', $text, $matches)){
            $link = explode('|', $matches[1]);
            $href = $link[0];
            $name = count($link) > 1 ? $this->parseInline(trim($link[1])) : $href;
            return [$this->createLink($href, $name), strlen($matches[0])];
        }
        return ['[[', 2];
    }

    protected function parseImpliedLink($text)
    {
        if ($end = strpos($text, ' ')){
            $link = substr($text, 0, $end);
            return [$this->createLink($link, $link), $end];
        }
        return [$this->createLink($text, $text), strlen($text)];
    }

    protected function parseNewLine($text)
    {
        return ['<br>', 2];
    }

    protected function parseNoFormat($text)
    {
        if (preg_match('/^[\{]{3}(.*?)[\}]{3}/', $text, $matches)){
            return [$matches[1], strlen($matches[0])];
        }
        return ['{{{', 3];
    }

    protected function parseMonospace($text)
    {
        if (preg_match('/^[#]{2}(.+?)[#]{2}/', $text, $matches)){
            return ['<span class="monospace">' . $this->parseInline($matches[1]) . '</span>', strlen($matches[0])];
        }
        return ['##', 2];
    }

    protected function parseSuperscript($text)
    {
        if (preg_match('/^[\^]{2}(.*?)[\^]{2}/', $text, $matches)){
            return ['<sup>' . $this->parseInline($matches[1]) . '</sup>', strlen($matches[0])];
        }
        return ['^^', 2];
    }

    protected function parseSubscript($text)
    {
        if (preg_match('/^[,]{2}(.*?)[,]{2}/', $text, $matches)){
            return ['<sub>' . $this->parseInline($matches[1]) . '</sub>', strlen($matches[0])];
        }
        return [',,', 2];
    }

    protected function parseUnderline($text)
    {
        if (preg_match('/^[_]{2}(.+?)[_]{2}/', $text, $matches)){
            return ['<span class="underline">' . $this->parseInline($matches[1]) . '</span>', strlen($matches[0])];
        }
        return ['__', 2];
    }

    protected function parseAsset($text)
    {
        // TODO Change this
        return '';
    }

    protected function parseEscape($text)
    {
        if (isset($text[1])){
            return [$text[1], 2];
        }
        return ['~', 1];
    }

    protected function parseCode($text)
    {
        if (preg_match('/^[`]{1}(.+?)[`]{1}/', $text, $matches)){
            return ['<code>' . $matches[1] . '</code>', strlen($matches[0])];
        }
        return ['`', 1];
    }

    protected function createLink($link, $name)
    {
        $link = $this->getLink($link);
        $link[1] = $link[1] ? '' : ' class="not-found"';
        return '<a href="' . $link[0] . '"' . $link[1] . '>' . $name . '</a>';
    }

    protected function getLink($link)
    {
        if (substr($link, 0, 4) === 'http'){
            return [$link, true];
        }

        if ($this->internalLinkExists($link)){
            return [$this->createInternalLink($link), true];
        }

        return ['#', false];
    }

    protected function internalLinkExists($link)
    {
        $call = $this->internalLinkExistsCall;
        if (!is_callable($call)){
            return false;
        }
        return $this->$call($link);
    }

    protected function createInternalLink($link)
    {
        $call = $this->createInternalLinkCall;
        if (!is_callable($call)){
            return '#';
        }
        return $call($link);
    }

    public function registerInternalLinkExists(callable $call)
    {
        $this->internalLinkExistsCall = $call;
    }

    public function registerCreateInternalLink(callable $call)
    {
        $this->createInternalLinkCall = $call;
    }
}