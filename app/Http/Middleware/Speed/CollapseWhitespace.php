<?php

namespace App\Http\Middleware\Speed;

class CollapseWhitespace extends PageSpeed
{
    public function apply($buffer)
    {
        $protectedBlocks = [];

        // Preserve <pre> and <textarea>, and minify JSON-LD inside <script type="application/ld+json">
        $buffer = preg_replace_callback(
            '#<(script|style|pre|textarea)\b([^>]*)>(.*?)</\1>#is',
            function (array $matches) use (&$protectedBlocks): string {
                $tag = strtolower($matches[1]);
                $attrs = $matches[2];
                $inner = $matches[3];

                if ($tag === 'script' && stripos($attrs, 'application/ld+json') !== false) {
                    $decoded = json_decode(trim($inner), true);
                    if ($decoded !== null) {
                        $inner = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                }

                $token = '___PROTECTED_BLOCK_' . count($protectedBlocks) . '___';
                $protectedBlocks[$token] = '<' . $matches[1] . $attrs . '>' . $inner . '</' . $matches[1] . '>';
                return $token;
            },
            $buffer
        ) ?? $buffer;

        $buffer = $this->removeComments($buffer);

        $replace = [
            "/\r\n|\r|\n|\t/" => ' ',
            "/ {2,}/" => ' ',
            "/> +</" => '><',
        ];

        $buffer = trim($this->replace($replace, $buffer));

        if (!empty($protectedBlocks)) {
            $buffer = strtr($buffer, $protectedBlocks);
        }

        return $buffer;
    }

    protected function removeComments($buffer)
    {
        return (new RemoveComments())->apply($buffer);
    }
}
