<?php

namespace Develate\CommonmarkCustomtagsDaisyui;

use Develate\CommonmarkCustomtags\CustomtagExtension;

final class CommonmarkDaisyuiExtension extends CustomtagExtension
{
    public function __construct(
        $globals = null,
        array $tags = [],
        string $openingDelimiter = '{{',
        string $closingDelimiter = '}}'
    ) {
        parent::__construct($globals, [], $openingDelimiter, $closingDelimiter);

        foreach ($this->daisyuiTags() as $identifier => $renderer) {
            $this->addTag(new DaisyuiComponentTag($identifier, $renderer));
        }

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * @return array<string, \Closure(array, mixed): string>
     */
    private function daisyuiTags(): array
    {
        return [
            'alert' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('alert', DaisyuiHtml::variant($a, 'alert'), DaisyuiHtml::mods($a, ['outline', 'dash', 'soft']), $a['class'] ?? null),
                DaisyuiHtml::join([
                    DaisyuiHtml::optionalSpan($a['icon'] ?? null),
                    DaisyuiHtml::wrap('div', null, DaisyuiHtml::join([
                        DaisyuiHtml::optionalSpan($a['title'] ?? null, 'font-bold'),
                        DaisyuiHtml::optionalSpan(DaisyuiHtml::text($a)),
                    ])),
                    DaisyuiHtml::optionalSpan($a['action'] ?? null),
                ]),
                DaisyuiHtml::componentAttrs($a)
            ),
            'avatar' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('avatar', DaisyuiHtml::when($a, 'placeholder'), $a['class'] ?? null),
                isset($a['src'])
                    ? DaisyuiHtml::wrap('div', DaisyuiHtml::classes($a['size'] ?? 'w-12', $a['shape'] ?? 'rounded-full'), DaisyuiHtml::void('img', ['src' => $a['src'], 'alt' => $a['alt'] ?? '']))
                    : DaisyuiHtml::wrap('div', DaisyuiHtml::classes('bg-neutral text-neutral-content', $a['size'] ?? 'w-12', $a['shape'] ?? 'rounded-full'), DaisyuiHtml::wrap('span', null, DaisyuiHtml::text($a, 'initials'))),
                DaisyuiHtml::componentAttrs($a)
            ),
            'badge' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'span',
                DaisyuiHtml::classes('badge', DaisyuiHtml::variant($a, 'badge'), DaisyuiHtml::size($a, 'badge'), DaisyuiHtml::mods($a, ['outline', 'dash', 'soft']), $a['class'] ?? null),
                DaisyuiHtml::text($a),
                DaisyuiHtml::componentAttrs($a)
            ),
            'button' => fn (array $a, $g): string => DaisyuiHtml::button($a),
            'card' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('card bg-base-100', $a['shadow'] ?? 'shadow-sm', $a['class'] ?? null),
                DaisyuiHtml::join([
                    isset($a['src']) ? DaisyuiHtml::wrap('figure', null, DaisyuiHtml::void('img', ['src' => $a['src'], 'alt' => $a['alt'] ?? ''])) : '',
                    DaisyuiHtml::wrap('div', 'card-body', DaisyuiHtml::join([
                        DaisyuiHtml::optionalWrap('h2', $a['title'] ?? null, 'card-title'),
                        DaisyuiHtml::optionalSpan(DaisyuiHtml::text($a)),
                        isset($a['action']) ? DaisyuiHtml::wrap('div', 'card-actions justify-end', DaisyuiHtml::button(['text' => $a['action'], 'href' => $a['href'] ?? null, 'type' => $a['action_type'] ?? 'primary'])) : '',
                    ])),
                ]),
                DaisyuiHtml::componentAttrs($a)
            ),
            'chat' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('chat', 'chat-' . DaisyuiHtml::token($a['side'] ?? 'start'), $a['class'] ?? null),
                DaisyuiHtml::join([
                    DaisyuiHtml::optionalWrap('div', $a['header'] ?? null, 'chat-header'),
                    DaisyuiHtml::wrap('div', DaisyuiHtml::classes('chat-bubble', DaisyuiHtml::variant($a, 'chat-bubble')), DaisyuiHtml::text($a)),
                    DaisyuiHtml::optionalWrap('div', $a['footer'] ?? null, 'chat-footer opacity-50'),
                ]),
                DaisyuiHtml::componentAttrs($a)
            ),
            'collapse' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('collapse bg-base-100 border border-base-300', 'collapse-' . DaisyuiHtml::token($a['icon'] ?? 'arrow'), $a['class'] ?? null),
                DaisyuiHtml::void('input', ['type' => 'checkbox', 'checked' => DaisyuiHtml::bool($a['open'] ?? false) ? 'checked' : null])
                . DaisyuiHtml::optionalWrap('div', $a['title'] ?? null, 'collapse-title font-semibold')
                . DaisyuiHtml::wrap('div', 'collapse-content text-sm', DaisyuiHtml::text($a)),
                DaisyuiHtml::componentAttrs($a)
            ),
            'countdown' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'span',
                DaisyuiHtml::classes('countdown font-mono', $a['class'] ?? null),
                DaisyuiHtml::wrap('span', null, '', ['style' => '--value:' . (int) ($a['value'] ?? $a[0] ?? 0)]),
                DaisyuiHtml::componentAttrs($a)
            ),
            'indicator' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('indicator', $a['class'] ?? null),
                DaisyuiHtml::wrap('span', DaisyuiHtml::classes('indicator-item badge', DaisyuiHtml::variant($a, 'badge')), DaisyuiHtml::text($a, 'badge'))
                . DaisyuiHtml::wrap('span', $a['content_class'] ?? 'btn', DaisyuiHtml::text($a, 'content')),
                DaisyuiHtml::componentAttrs($a)
            ),
            'kbd' => fn (array $a, $g): string => DaisyuiHtml::wrap('kbd', DaisyuiHtml::classes('kbd', DaisyuiHtml::size($a, 'kbd'), $a['class'] ?? null), DaisyuiHtml::text($a), DaisyuiHtml::componentAttrs($a)),
            'link' => fn (array $a, $g): string => DaisyuiHtml::wrap('a', DaisyuiHtml::classes('link', DaisyuiHtml::variant($a, 'link'), $a['class'] ?? null), DaisyuiHtml::text($a), DaisyuiHtml::componentAttrs($a, ['href' => $a['href'] ?? '#'])),
            'loading' => fn (array $a, $g): string => DaisyuiHtml::wrap('span', DaisyuiHtml::classes('loading', 'loading-' . DaisyuiHtml::token($a['style'] ?? 'spinner'), DaisyuiHtml::size($a, 'loading'), $a['class'] ?? null), '', DaisyuiHtml::componentAttrs($a, ['aria-label' => $a['label'] ?? 'Loading'])),
            'mask' => fn (array $a, $g): string => DaisyuiHtml::void('img', DaisyuiHtml::componentAttrs($a, ['src' => $a['src'] ?? '', 'alt' => $a['alt'] ?? '', 'class' => DaisyuiHtml::classes('mask', 'mask-' . DaisyuiHtml::token($a['shape'] ?? 'squircle'), $a['class'] ?? null)])),
            'mockup-code' => fn (array $a, $g): string => DaisyuiHtml::wrap(
                'div',
                DaisyuiHtml::classes('mockup-code', $a['class'] ?? null),
                DaisyuiHtml::lines($a, fn (string $line): string => DaisyuiHtml::wrap('pre', null, DaisyuiHtml::wrap('code', null, $line))),
                DaisyuiHtml::componentAttrs($a)
            ),
            'progress' => fn (array $a, $g): string => DaisyuiHtml::wrap('progress', DaisyuiHtml::classes('progress', DaisyuiHtml::variant($a, 'progress'), $a['class'] ?? null), '', DaisyuiHtml::componentAttrs($a, ['value' => $a['value'] ?? $a[0] ?? null, 'max' => $a['max'] ?? 100])),
            'radial-progress' => fn (array $a, $g): string => DaisyuiHtml::wrap('div', DaisyuiHtml::classes('radial-progress', DaisyuiHtml::variant($a, 'text'), $a['class'] ?? null), DaisyuiHtml::text($a, default: (string) ($a['value'] ?? $a[0] ?? 0) . '%'), DaisyuiHtml::componentAttrs($a, ['role' => 'progressbar', 'style' => DaisyuiHtml::style(['--value' => (int) ($a['value'] ?? $a[0] ?? 0), '--size' => $a['size'] ?? null, '--thickness' => $a['thickness'] ?? null])])),
            'rating' => fn (array $a, $g): string => DaisyuiHtml::rating($a),
            'stat' => fn (array $a, $g): string => DaisyuiHtml::wrap('div', DaisyuiHtml::classes('stats shadow', $a['class'] ?? null), DaisyuiHtml::wrap('div', 'stat', DaisyuiHtml::optionalWrap('div', $a['title'] ?? null, 'stat-title') . DaisyuiHtml::optionalWrap('div', $a['value'] ?? $a[0] ?? null, 'stat-value') . DaisyuiHtml::optionalWrap('div', $a['desc'] ?? null, 'stat-desc')), DaisyuiHtml::componentAttrs($a)),
            'steps' => fn (array $a, $g): string => DaisyuiHtml::wrap('ul', DaisyuiHtml::classes('steps', DaisyuiHtml::when($a, 'vertical', 'steps-vertical'), $a['class'] ?? null), DaisyuiHtml::steps($a), DaisyuiHtml::componentAttrs($a)),
            'table' => fn (array $a, $g): string => DaisyuiHtml::table($a),
            'tabs' => fn (array $a, $g): string => DaisyuiHtml::tabs($a),
            'toast' => fn (array $a, $g): string => DaisyuiHtml::wrap('div', DaisyuiHtml::classes('toast', 'toast-' . DaisyuiHtml::token($a['x'] ?? 'end'), 'toast-' . DaisyuiHtml::token($a['y'] ?? 'bottom'), $a['class'] ?? null), DaisyuiHtml::wrap('div', DaisyuiHtml::classes('alert', DaisyuiHtml::variant($a, 'alert')), DaisyuiHtml::wrap('span', null, DaisyuiHtml::text($a))), DaisyuiHtml::componentAttrs($a)),
            'tooltip' => fn (array $a, $g): string => DaisyuiHtml::wrap('span', DaisyuiHtml::classes('tooltip', isset($a['position']) ? 'tooltip-' . DaisyuiHtml::token($a['position']) : null, DaisyuiHtml::variant($a, 'tooltip'), $a['class'] ?? null), DaisyuiHtml::text($a), DaisyuiHtml::componentAttrs($a, ['data-tip' => $a['tip'] ?? $a['label'] ?? ''])),
        ];
    }
}
