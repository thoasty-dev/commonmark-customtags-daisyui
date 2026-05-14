<?php

namespace Develate\CommonmarkCustomtagsDaisyui;

use Develate\CommonmarkCustomtags\Customtag;

final class DaisyuiComponentTag extends Customtag
{
    public function __construct(private string $identifier, private \Closure $renderer)
    {
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function render($arguments, $globals): \Stringable|string|null
    {
        return ($this->renderer)($arguments, $globals);
    }
}
