<?php

require __DIR__ . '/../vendor/autoload.php';

use Develate\CommonmarkCustomtagsDaisyui\CommonmarkDaisyuiExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

function renderMarkdown(string $markdown): string
{
    $environment = new Environment([]);
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new CommonmarkDaisyuiExtension());

    return trim((string) (new MarkdownConverter($environment))->convert($markdown));
}

function assertSameValue(string $label, string $expected, string $actual): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(sprintf(
            "%s\nExpected: %s\nActual:   %s",
            $label,
            $expected,
            $actual
        ));
    }
}

function assertContainsValue(string $label, string $needle, string $actual): void
{
    if (!str_contains($actual, $needle)) {
        throw new RuntimeException(sprintf(
            "%s\nExpected to contain: %s\nActual:             %s",
            $label,
            $needle,
            $actual
        ));
    }
}

$tests = [
    'badge renders daisyui classes' => fn () => assertSameValue(
        'badge renders daisyui classes',
        '<p><span class="badge badge-primary badge-lg">Ready now</span></p>',
        renderMarkdown('{{badge Ready%20now type=primary size=lg}}')
    ),
    'button renders anchor when href exists' => fn () => assertSameValue(
        'button renders anchor when href exists',
        '<p><a class="btn btn-success btn-sm" href="/save">Save</a></p>',
        renderMarkdown('{{button Save href=/save type=success size=sm}}')
    ),
    'badge renders optional class and id attributes' => fn () => assertSameValue(
        'badge renders optional class and id attributes',
        '<p><span class="badge extra-class" id="status-badge">Ready</span></p>',
        renderMarkdown('{{badge Ready class=extra-class id=status-badge}}')
    ),
    'alert escapes user text' => fn () => assertSameValue(
        'alert escapes user text',
        '<p><div class="alert alert-warning"><div><span>&lt;Check&gt;</span></div></div></p>',
        renderMarkdown('{{alert text=%3CCheck%3E type=warning}}')
    ),
    'steps renders pipe separated items' => fn () => assertSameValue(
        'steps renders pipe separated items',
        '<p><ul class="steps"><li class="step step-primary">Plan</li><li class="step step-primary">Build</li><li class="step">Ship</li></ul></p>',
        renderMarkdown('{{steps items=Plan|Build|Ship current=2}}')
    ),
    'table renders headers and rows' => fn () => assertSameValue(
        'table renders headers and rows',
        '<p><div class="overflow-x-auto"><table class="table table-zebra"><thead><tr><th>Name</th><th>Status</th></tr></thead><tbody><tr><td>API</td><td>OK</td></tr></tbody></table></div></p>',
        renderMarkdown('{{table headers=Name|Status rows=API,OK zebra=true}}')
    ),
    'all components render optional id attributes' => function (): void {
        $components = [
            'alert' => '{{alert text=Alert id=component-id}}',
            'avatar' => '{{avatar initials=AK id=component-id}}',
            'badge' => '{{badge Badge id=component-id}}',
            'button' => '{{button Button id=component-id}}',
            'card' => '{{card text=Card id=component-id}}',
            'chat' => '{{chat text=Chat id=component-id}}',
            'collapse' => '{{collapse title=Title text=Content id=component-id}}',
            'countdown' => '{{countdown value=7 id=component-id}}',
            'indicator' => '{{indicator badge=1 content=Inbox id=component-id}}',
            'kbd' => '{{kbd Esc id=component-id}}',
            'link' => '{{link Link href=/docs id=component-id}}',
            'loading' => '{{loading id=component-id}}',
            'mask' => '{{mask src=/avatar.png alt=Avatar id=component-id}}',
            'mockup-code' => '{{mockup-code items=echo id=component-id}}',
            'progress' => '{{progress value=50 id=component-id}}',
            'radial-progress' => '{{radial-progress value=50 id=component-id}}',
            'rating' => '{{rating value=3 id=component-id}}',
            'stat' => '{{stat value=42 id=component-id}}',
            'steps' => '{{steps items=Plan|Build current=1 id=component-id}}',
            'table' => '{{table headers=Name rows=API id=component-id}}',
            'tabs' => '{{tabs items=One|Two id=component-id}}',
            'toast' => '{{toast text=Saved id=component-id}}',
            'tooltip' => '{{tooltip text=Hover tip=Details id=component-id}}',
        ];

        foreach ($components as $component => $markdown) {
            assertContainsValue(
                sprintf('%s renders optional id attribute', $component),
                'id="component-id"',
                renderMarkdown($markdown)
            );
        }
    },
];

$failures = [];

foreach ($tests as $label => $test) {
    try {
        $test();
        echo '.';
    } catch (Throwable $exception) {
        $failures[$label] = $exception;
        echo 'F';
    }
}

echo PHP_EOL;

if ($failures !== []) {
    foreach ($failures as $label => $exception) {
        fwrite(STDERR, PHP_EOL . $label . PHP_EOL . $exception->getMessage() . PHP_EOL);
    }

    exit(1);
}

echo sprintf('OK (%d tests)' . PHP_EOL, count($tests));
