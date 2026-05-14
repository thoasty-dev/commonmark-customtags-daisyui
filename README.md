# commonmark-customtags-daisyui

PHP CommonMark custom tags for [daisyUI](https://daisyui.com/).

## Installation

```bash
composer require develate/commonmark-customtags-daisyui
```

## Usage

```php
<?php

use Develate\CommonmarkCustomtagsDaisyui\CommonmarkDaisyuiExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

$environment = new Environment([]);
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new CommonmarkDaisyuiExtension());

$converter = new MarkdownConverter($environment);

echo $converter->convert('Status: {{badge Ready type=success}}');
```

Output:

```html
<p>Status: <span class="badge badge-success">Ready</span></p>
```

## Tags

All values with spaces must be URL-encoded, for example `Ready%20now`.

```markdown
{{alert text=Saved type=success title=Done}}
{{avatar src=/avatar.jpg alt=Thorsten size=w-16}}
{{badge Ready type=primary size=lg}}
{{button Save href=/save type=success size=sm}}
{{card title=Release text=Ready action=Open href=/release}}
{{chat text=Hello side=end type=primary}}
{{collapse title=Details text=More%20info open=true}}
{{countdown value=42}}
{{indicator badge=3 content=Inbox type=secondary}}
{{kbd Ctrl}}
{{link Docs href=/docs type=primary}}
{{loading style=spinner size=md}}
{{mask src=/photo.jpg shape=squircle}}
{{mockup-code items=composer%20install|php%20tests/run.php}}
{{progress value=70 max=100 type=success}}
{{radial-progress value=70}}
{{rating value=4 max=5 type=warning}}
{{stat title=Downloads value=1200 desc=This%20week}}
{{steps items=Plan|Build|Ship current=2}}
{{table headers=Name|Status rows=API,OK;Web,Ready zebra=true}}
{{tabs items=Preview|Code active=0}}
{{toast text=Saved type=success}}
{{tooltip text=Hover tip=Extra%20info position=top}}
```

Most components accept:

- `type`, `color`, or `variant` for daisyUI variants such as `primary`, `secondary`, `accent`, `success`, `warning`, `error`, `info`, `neutral`
- `size` for daisyUI sizes such as `xs`, `sm`, `md`, `lg`, `xl`
- `class` for extra utility classes
