<?php

namespace App\Http\Middleware\Speed;

class CollapseWhitespace extends PageSpeed
{
    public function apply($buffer)
    {
        $protectedBlocks = [];

        // Preserve <script>, <style>, <pre>, and <textarea> blocks verbatim
        $buffer = preg_replace_callback(
            '#<(script|style|pre|textarea)\b[^>]*>.*?</\1>#is',
            function (array $matches) use (&$protectedBlocks): string {
                $token = '___PROTECTED_BLOCK_' . count($protectedBlocks) . '___';
                $protectedBlocks[$token] = $matches[0];
                return $token;
            },
            $buffer
        ) ?? $buffer;

        $buffer = $this->removeComments($buffer);

        $replace = [
            "/\r\n|\r|\n|\t/" => ' ',
            "/ {2,}/" => ' ',
        ];

        $buffer = $this->replace($replace, $buffer);

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
