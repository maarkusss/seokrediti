<?php

namespace App\Http\Middleware\Speed;

class RemoveComments extends PageSpeed
{
    public const REGEX_MATCH_HTML_COMMENTS = '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s';

    public function apply($buffer)
    {
        return $this->replace([
            self::REGEX_MATCH_HTML_COMMENTS => '',
        ], $buffer);
    }
}
