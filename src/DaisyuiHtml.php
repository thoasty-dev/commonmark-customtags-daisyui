<?php

namespace Develate\CommonmarkCustomtagsDaisyui;

final class DaisyuiHtml
{
    public static function button(array $a): string
    {
        $class = self::classes('btn', self::variant($a, 'btn'), self::size($a, 'btn'), self::mods($a, ['outline', 'dash', 'soft', 'ghost', 'link', 'active', 'wide', 'block', 'circle', 'square']), $a['class'] ?? null);
        $content = self::bool($a['loading'] ?? false) ? self::wrap('span', 'loading loading-spinner', '') : '';
        $content .= self::text($a, 'label');

        if (!empty($a['href'])) {
            return self::wrap('a', $class, $content, self::componentAttrs($a, ['href' => $a['href'], 'target' => $a['target'] ?? null, 'rel' => $a['rel'] ?? null]));
        }

        return self::wrap('button', $class, $content, self::componentAttrs($a, ['type' => $a['button_type'] ?? 'button', 'disabled' => self::bool($a['disabled'] ?? false) ? 'disabled' : null]));
    }

    public static function rating(array $a): string
    {
        $name = self::token($a['name'] ?? 'rating');
        $max = max(1, (int) ($a['max'] ?? 5));
        $value = (int) ($a['value'] ?? 0);
        $items = '';

        for ($i = 1; $i <= $max; $i++) {
            $items .= self::void('input', [
                'type' => 'radio',
                'name' => $name,
                'class' => self::classes('mask', 'mask-' . self::token($a['shape'] ?? 'star-2'), self::variant($a, 'bg')),
                'checked' => $i === $value ? 'checked' : null,
                'aria-label' => (string) $i,
            ]);
        }

        return self::wrap('div', self::classes('rating', self::size($a, 'rating'), $a['class'] ?? null), $items, self::componentAttrs($a));
    }

    public static function steps(array $a): string
    {
        $current = (int) ($a['current'] ?? 0);
        $items = self::split($a['items'] ?? $a[0] ?? '');
        $html = '';

        foreach ($items as $index => $item) {
            $html .= self::wrap('li', self::classes('step', $index < $current ? self::variant($a, 'step', 'primary') : null), $item);
        }

        return $html;
    }

    public static function table(array $a): string
    {
        $headers = self::split($a['headers'] ?? '');
        $rows = self::split($a['rows'] ?? '', ';');
        $head = $headers === [] ? '' : self::wrap('thead', null, self::wrap('tr', null, self::lines(['items' => implode('|', $headers)], fn (string $h): string => self::wrap('th', null, $h))));
        $body = '';

        foreach ($rows as $row) {
            $body .= self::wrap('tr', null, self::lines(['items' => str_replace(',', '|', $row)], fn (string $cell): string => self::wrap('td', null, $cell)));
        }

        return self::wrap('div', 'overflow-x-auto', self::wrap('table', self::classes('table', self::when($a, 'zebra', 'table-zebra'), $a['class'] ?? null), $head . self::wrap('tbody', null, $body), self::componentAttrs($a)));
    }

    public static function tabs(array $a): string
    {
        $items = self::split($a['items'] ?? $a[0] ?? '');
        $active = (int) ($a['active'] ?? 0);
        $html = '';

        foreach ($items as $index => $item) {
            $html .= self::wrap('a', self::classes('tab', $index === $active ? 'tab-active' : null), $item, ['href' => '#']);
        }

        return self::wrap('div', self::classes('tabs', 'tabs-' . self::token($a['style'] ?? 'border'), $a['class'] ?? null), $html, self::componentAttrs($a));
    }

    public static function lines(array $a, callable $renderer): string
    {
        return self::join(array_map($renderer, self::split($a['items'] ?? $a['lines'] ?? $a[0] ?? '')));
    }

    public static function text(array $a, string $key = 'text', ?string $default = ''): string
    {
        return self::escape((string) ($a[$key] ?? $a[0] ?? $default));
    }

    public static function optionalSpan(?string $value, ?string $class = null): string
    {
        return $value === null || $value === '' ? '' : self::wrap('span', $class, self::escape($value));
    }

    public static function optionalWrap(string $tag, $value, ?string $class = null): string
    {
        return $value === null || $value === '' ? '' : self::wrap($tag, $class, self::escape((string) $value));
    }

    public static function wrap(string $tag, ?string $class, string $content, array $attrs = []): string
    {
        if ($class !== null && $class !== '') {
            $attrs = ['class' => $class] + $attrs;
        }

        return '<' . $tag . self::attrs($attrs) . '>' . $content . '</' . $tag . '>';
    }

    public static function void(string $tag, array $attrs): string
    {
        return '<' . $tag . self::attrs($attrs) . '>';
    }

    public static function attrs(array $attrs): string
    {
        $html = '';

        foreach ($attrs as $name => $value) {
            if ($value === null || $value === false || $value === '') {
                continue;
            }

            $html .= ' ' . self::token((string) $name) . '="' . self::escape((string) $value) . '"';
        }

        return $html;
    }

    public static function componentAttrs(array $a, array $attrs = []): array
    {
        return ['id' => $a['id'] ?? null] + $attrs;
    }

    public static function classes(...$classes): string
    {
        $tokens = [];

        foreach ($classes as $class) {
            foreach (preg_split('/\s+/', (string) $class) ?: [] as $token) {
                $token = self::token($token);
                if ($token !== '') {
                    $tokens[] = $token;
                }
            }
        }

        return implode(' ', array_unique($tokens));
    }

    public static function variant(array $a, string $prefix, ?string $default = null): ?string
    {
        $value = $a['type'] ?? $a['color'] ?? $a['variant'] ?? $default;
        return $value === null || $value === '' ? null : $prefix . '-' . self::token($value);
    }

    public static function size(array $a, string $prefix): ?string
    {
        return empty($a['size']) ? null : $prefix . '-' . self::token($a['size']);
    }

    public static function mods(array $a, array $mods): string
    {
        return self::classes(...array_map(fn (string $mod): ?string => self::bool($a[$mod] ?? false) ? $mod : null, $mods));
    }

    public static function when(array $a, string $key, ?string $class = null): ?string
    {
        return self::bool($a[$key] ?? false) ? ($class ?? $key) : null;
    }

    public static function style(array $values): string
    {
        $style = [];

        foreach ($values as $name => $value) {
            if ($value !== null && $value !== '') {
                $style[] = self::token((string) $name) . ':' . self::token((string) $value);
            }
        }

        return implode(';', $style);
    }

    public static function split(string $value, string $separator = '|'): array
    {
        return array_values(array_filter(array_map('trim', explode($separator, $value)), fn (string $item): bool => $item !== ''));
    }

    public static function bool($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function token(string $value): string
    {
        return preg_replace('/[^a-zA-Z0-9_:\-\/\[\]%.#]/', '', $value) ?? '';
    }

    public static function join(array $parts): string
    {
        return implode('', array_filter($parts, fn (string $part): bool => $part !== ''));
    }

    public static function escape(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
