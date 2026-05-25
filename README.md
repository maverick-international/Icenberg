# Icenberg

![Static Badge](https://img.shields.io/badge/forced_to-wordpress-blue?style=flat&logo=wordpress&logoColor=white)
![Packagist Version](https://img.shields.io/packagist/v/mvrk/icenberg)

Icenberg is an opinionated abstraction library for writing websites with Advanced Custom Fields in WordPress. It cleans
up and simplifies ACF Flexible content and ACF Gutenberg block templates which often involve a lot of repetition and
logic tangled up in presentation (in true WordPress style) by handling the rendering of blocks and fields for you.

Using Icenberg's element methods you can render acf fields and blocks inside a block, or anywhere you choose, fully
scaffolded with BEM classes and settings which makes writing structured and re-usable CSS much more manageable. Icenberg
takes care of checking a fields existence and skips over non-rendering fields. It can even apply settings as modifier
classes.

```php

$icenberg->the_element('client_logos');

```

Using the CLI you can generate full-featured ACF Gutenberg blocks (or flexible content row templates) with all the
necessary files and with the boilerplate abstracted and settings applied in one command.

```bash
wp icenberg block testimonial
```

## Who is it for?

Icenberg is written by professional (human) web developers who use it every day. It is intended for use by other
professional or hobbyist web developers who build WordPress themes, by choice or otherwise. If you don't understand the
problem it solves, don't like writing (non boilerplate) code or prefer Tailwind to BEM then this probably isn't for you.
This is not a WordPress plugin.

## Requirements

- PHP 8+
- ACF Pro 6+
- WordPress 7+
- Composer
- WP CLI

## Getting Started

Install via composer:

```bash
composer require mvrk/icenberg
```

Make sure autoloading is set up in functions.php - something like:

```php

$composer_path = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/';

if (file_exists($composer_path)) {
    require_once $composer_path . 'autoload.php';
}

```

Initialise in functions.php

```php

\MVRK\Icenberg\Hooks::init();

```

Optionally add the CLI commands

```php

$is_wp_cli = defined('WP_CLI') && WP_CLI;

if ($is_wp_cli) {
    \MVRK\Icenberg\Commands\Bootstrap::setup();
}

``` 

Use in a template

```php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg($layout = 'block_name');

```

## Configuration

---

To configure for your own preferences and environment, place an 'icenberg.yaml' file in your project's root directory.
If this file doesn't exist or can't be parsed, icenberg will just go ahead and use its defaults.

Supported config options below with their default values:

```yaml
#icenberg.yaml

block_directory_name: 'blocks' #change the default block directory name
block_editor_prefix: "block" #wordpress will use this as the prefix for blocks in the block editor
sass_path: 'src/sass/blocks' #specify a location for sass partials
google_maps_api_key: '<your key here>' #if you want to use the maps field

```

## Basic Usage

---

## Gutenberg Block

You can use Icenberg in an ACF Gutenberg block render template, whether generated using the CLI or hand made (you should
try the CLI). You can use the wrap method in a Gutenberg block to wrap the block frontend in a similar way to how it is
wrapped automatically by wp in the backend.

```php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg(strtolower($block['title']));
$wrapped = true;

$ic->wrap(
    [
        $ice->get_element('quote'),
        $ice->get_element('attribution'),
        $ice->get_element('portrait_image'),
    ],
    $block,
    $wrapped
);

```

### Flexible Content

The following example takes place inside ACF's the_row() - ie:

```php

if (have_rows('content_blocks', $id)) :

    while (have_rows('content_blocks', $id)) : the_row();

        get_template_part('inc/blocks/block_template');

    endwhile;

endif;

```

Initialise with ACFs `get_row_layout()` in your block template:

```php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg(get_row_layout());

$ice->the_element('quote');
$ice->the_element('attribution');
$ice->the_element('portrait');

```

## API

### Icenberg()

instantiate an Icenberg object at block or template level.

```php

$ice = new Icenberg($layout = "testimonial", $prefix = "block", $post_id = false)

```

| Argument   | Type   | Required | Description                                           |
|------------|--------|----------|-------------------------------------------------------|
| `$layout`  | string | Yes      | ACF layout or block name                              |
| `$prefix`  | string | No       | Prefix used for classnames etc. Defaults to `'block'` |
| `$post_id` | mixed  | No       | Override the default post ID. Defaults to `false`     |

### get_element()

Returns an ACF field as a formatted string, wrapped up in all the divs you need and with any special considerations
applied. Takes the field name as an argument and optionally a tag for the uppermost element. If no tag is set it will
use 'div'

```php

$field_name = $ice->get_element('field_name');

echo $field_name;

```

| Argument      | Type   | Required | Description                                              |
|---------------|--------|----------|----------------------------------------------------------|
| `$field_name` | string | Yes      | The ACF field name to render                             |
| `$tag`        | string | No       | The HTML tag to wrap the element in. Defaults to `'div'` |
| `$modifiers`  | array  | No       | An array of modifiers to use as BEM classes              |

### the_element()

As above, but echoes it out immediately.

```php

$ice->the_element('field_name');

```

| Argument      | Type   | Required | Description                                              |
|---------------|--------|----------|----------------------------------------------------------|
| `$field_name` | string | Yes      | The ACF field name to echo                               |
| `$tag`        | string | No       | The HTML tag to wrap the element in. Defaults to `'div'` |
| `$modifiers`  | array  | No       | An array of modifiers to use as BEM classes              |

Icenberg is smart enough to know what a field's type is, so you don't need to differentiate, you just pass the field
name in. You can pass ANY field. If the field is supported, Icenberg will process the field and its sub-fields

To retrieve a global option simply pass a comma-delimited string to `the_element()` or `get_element()` - it must be all
as one string as opposed to separate args, and it must be comma-delimited, where the first part is the field name and
the second part is the options name.

```php

$ice->the_element('field_name, options');

```

### enclose() / get_enclose()

Enclose is a utility for wrapping multiple icenberg fields in a container div without having to use a ?> anywhere. You
just need to pass it a classname (without prefixes as these will be applied by icenberg). So clean!

| Argument     | Type   | Required | Description                                                                      |
|--------------|--------|----------|----------------------------------------------------------------------------------|
| `$class`     | string | Yes      | Classname to apply to wrapper div (BEM modifiers will be appended automatically) |
| `$elements`  | array  | Yes      | Array of rendered HTML elements (e.g. `get_element()` results or strings)        |
| `$tag`       | string | No       | The HTML tag to wrap the element in. Defaults to `'div'`                         |
| `$attrs`     | array  | No       | An array of additional attributes to apply to the wrapper element                |
| `$modifiers` | array  | No       | An array of modifiers to use as BEM classes                                      |

So for example, in a 'Cta' block, where cta_heading is a text field and cta_content is a WYSIWYG field:

```php

$ice->enclose('text', [
    $ice->get_element('cta_heading')
    $ice->get_element('cta_content'),
]);

```

will generate:

```html

<div class="block--cta__text">
    <div class="block--cta__cta-heading">
        I'm a heading, look at me!
    </div>
    <div class="block--cta__cta-content">
        <p>
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>
    </div>
</div>

```

You could also pass any thing else you like to enclose as part of the array, as long as its storable as a variable (for
example inserting a `get_template_part()` won't work here because it effectively prints the content).

```php

$random_text = "<span>I am some random text, isn't it wonderful?</span>";

$ice->enclose('text', [
    $ice->get_element('cta_heading'),
    $random_text
]);

```

Of course life is never simple, so you will most likely need more complex layouts, but icenberg doesn't mind. You can
insert it in html if you want to.

```html

<div class="testimonial__corner-illustration_green_reversed">
    <?php if($motif_variation_5_purple === 'orange') :
    $ice->the_element('motif_blurple');
    endif; ?>
</div>

```

### Value()

```php

$lady_in_red = $ice->value('dancing_with_me');

```

You can us the `value()` method to return the 'raw', value of a given field without checking for existence or
specifying if it is a sub-field. As this just returns the value there are no special considerations for individual
field types.

| Argument      | Type   | Required | Description       |
|---------------|--------|----------|-------------------|
| `$field_name` | string | Yes      | Name of the field |

### field()

 ```php

 $ice->field($field_name)

 ```

you can use icenberg to evaluate fields and do some more complex field manipulation too, using the `field()` method in
conjunction with the below methods. `field()` takes the field name as an argument and returns the icenberg instance for
method chaining.

| Argument      | Type   | Required | Description                    |
|---------------|--------|----------|--------------------------------|
| `$field_name` | string | Yes      | Name of the field to work with |

### get()

```php

$ice->field('saxophone')->get()

```

Returns the icenbergified field HTML (in the same way as get_element). Optionally pass a tag for the wrapper.

| Argument     | Type   | Required | Description                                     |
|--------------|--------|----------|-------------------------------------------------|
| `$tag`       | string | No       | Tag to use for the wrapper. Defaults to `'div'` |
| `$modifiers` | array  | No       | An array of modifiers to use as BEM classes     |

### prune()

```php

$group = $ice->field('bad_singers')->prune(['chris_de_burgh', 'cliff_richard'])->get();

```

Pass an array of field names to the prune method to remove them from a group or from a repeater row.

| Argument      | Type  | Required | Description                                           |
|---------------|-------|----------|-------------------------------------------------------|
| `$exclusions` | array | Yes      | Field names to exclude from the group/repeater output |

### only()

```php

$group = $ice->field('great_singers')->only(['chris_de_burgh'])->get('marquee');

```

Whitelist fields in a group

| Argument      | Type  | Required | Description                                         |
|---------------|-------|----------|-----------------------------------------------------|
| `$inclusions` | array | Yes      | Field names to include in the group/repeater output |

### is()

returns true if the value of the field equals the argument passed to `is()`. You don't need to check for a fields
existence before using these methods as they will do it for you and return `false` if they don't.

```php

if ($ice->field('font_colour')->is('red')) :
    $ice->the_element('font_colour');
else :
    echo 'oh no';
endif;

```

| Argument | Type  | Required | Description                              |
|----------|-------|----------|------------------------------------------|
| `$value` | mixed | Yes      | Value to compare against the field value |

### has()

Returns true if the field value (an array/iterable like a select multiple or checkbox field) contains the
given value. Returns false if the field doesn't exist or isn't iterable.

```php
if ($ice->field('mall')->has('cop')) :
    echo 'blart';
endif;
```

| Argument | Type  | Required | Description                              |
|----------|-------|----------|------------------------------------------|
| `$value` | mixed | Yes      | Value to compare against the field value |

### lessThan() / greaterThan()

Self-explanatory, both take an integer as an argument. Warning: If you use it on a non-numeric field it will return
false.

```php
if ($ice->field('range_test')->lessThan(51)) :
    $class = 'text_' . $ice->field('range_test')->field;
    $ice->enclose($class, [
        $ice->get_element('cta_content'),
        $ice->get_element('cta_image'),
    ]);
endif;
```

| Argument | Type | Required | Description                            |
|----------|------|----------|----------------------------------------|
| `$value` | int  | Yes      | Integer to compare against field value |

### settings()

Pass in a field group of settings and optionally an array of manually set classes, and it will attach them as CSS
modifier classes. if you include a text field called 'unique_id' in your group icenberg will attach it as an ID too.

| Argument          | Type  | Required | Description                                 |
|-------------------|-------|----------|---------------------------------------------|
| `$block_settings` | array | Yes      | Array of field group names                  |
| `$classes`        | array | Yes      | Arbitrary classnames to append as modifiers |

If using the `wrap()` method, you don't need to manually specify settings. `wrap()` looks for a field group called '
block_settings' and one called 'settings' on the block and uses this method to parse them into modifiers on the
intermediate wrapper.

Example using settings in  `enclose()`:

```php
$classes = ['banana', 'orange'];

$block_settings = get_sub_field($block_settings);

$settings = $ice->settings($block_settings, $classes);

$ice->enclose ($settings, [
    $ice->get_element('cheese_board'),
    $ice->get_element('flame_thrower')
])
```

or in regular php/html

```html

<div <?php echo $settings; ?>>
...whatever you want
</div>
```

which will print out something like

```html
"class='component--orange component--banana component--padding-top-300 component--skin-purple' id='some_id'"
```

Depending on the settings in your group.

### wrap()

Wraps an array of rendered elements in the standard Icenberg block wrapper, mirroring the wrapping that WordPress
applies in the backend so the frontend matches. Optionally accepts a background element which can be rendered behind the
wrapped content.

```php
$ice->wrap(
    [
        $ice->get_element('quote'),
        $ice->get_element('attribution'),
    ],
    $block,
    $wrap_inner = true,
    $background = $ice->get_element('background_image')
);
```

| Argument      | Type  | Required | Description                                                             |
|---------------|-------|----------|-------------------------------------------------------------------------|
| `$content`    | array | Yes      | HTML elements to wrap                                                   |
| `$block`      | array | No       | The acf block, automatically available in the render template           |
| `$wrap_inner` | bool  | No       | Whether to wrap the contents in an inner wrapper                        |
| `$background` | mixed | No       | A rendered element (e.g. an image) to place behind the wrapped content. |

### InnerBlocks

Helper for rendering Gutenberg's `<InnerBlocks/>` tag inside an ACF block render template, with the
appropriate BEM class applied automatically so it slots in with the rest of your block markup. Pass an array of
allowed block names and the current Icenberg instance.

```php
use MVRK\Icenberg\Icenberg;
use MVRK\Icenberg\Blocks\InnerBlocks;

$icenberg = new Icenberg('my_block');
$allowed_blocks = ['core/heading', 'core/paragraph'];

$content = $icenberg->get_enclose('content', [
    $icenberg->get_element('intro'),
    InnerBlocks::make($allowed_blocks, $icenberg),
]);

$icenberg->wrap([$content], $block);
```

| Argument          | Type     | Required | Description                                           |
|-------------------|----------|----------|-------------------------------------------------------|
| `$allowed_blocks` | array    | Yes      | Array of block names permitted inside the InnerBlocks |
| `$icenberg`       | Icenberg | Yes      | The current Icenberg instance                         |

### member()

Pulls a single sub-field value directly out of a group field without having to render the whole group. Useful when
you only need one value from a settings or config group.

```php
$accent = $ice->member('accent_colour', 'block_settings');
```

| Argument  | Type   | Required | Description                                           |
|-----------|--------|----------|-------------------------------------------------------|
| `$target` | string | Yes      | The sub-field name to retrieve (needle)               |
| `$group`  | string | Yes      | The name of the group field to look inside (haystack) |

## Special Fields

#### Google Maps Field

For the Google Maps field to work properly on front and backends you will need an API key. Once you have the key, add it
to icenberg.yml in your project and the frontend will work. To make it work in the backend, add the following to your
functions.php (in this example the key is defined in wp-config.php)

```php
function acf_google_map_field($api)
{
    $api['key'] = GOOGLE_MAPS_API_KEY;

    return $api;
}
add_filter('acf/fields/google_map/api', 'acf_google_map_field');
```

## CLI

you can use the Icenberg CLI to rapidly bootstrap Icenberg blocks. This extends wp-cli so you need to have that
installed and working first.

### Available commands

```shell
wp icenberg block --block_name
```

This bootstraps an ACF Gutenberg block with all relevant files ready to go.

This assumes that your blocks are in a folder called 'blocks' in your template root directory but this is configurable
via icenberg.yaml

Icenberg generates a folder per block within the 'blocks' folder. This folder contains

- block.json
- <block_name>.php
- <block_name>.css

It will also register an empty field group ready for access via the ACF GUI and send a scss file to the sass
directory. The block.json is opinionated so modify as required once its generated. Pay attention to the fact that the
stylesheet is set to display in-editor only because we're assuming a workflow where you're compiling sass to
the CSS file and enqueuing it globally on the frontend.

```shell 
wp icenberg block --block_name --flexible
```

this is similar to the above but creates just the php and scss files for flexible content blocks.

### Supported Add Ons

The library directly supports the following ACF plugins:

* [ACF Gravity forms](https://wordpress.org/plugins/acf-gravityforms-add-on/)
* [Table Field Add-on for ACF and SCF](https://wordpress.org/plugins/advanced-custom-fields-table-field/)

Other plugins may be compatible if they use the built-in field names or aren't directly involved in the render.

## Supported fields

#### Full Support

- Gallery
- Group
- Image
- Link
- Number
- OEmbed
- Range
- Repeater
- Select
- Text
- Textarea
- WYSIWYG
- Relationship
- Post Object
- Page Link
- Google Maps

Icenberg will quietly skip over any other default fields, such as password, that you are unlikely to want to render.

#### Third party fields:

- Forms
- Table

### VS Code Extension

An extension with useful snippets is available for Virtual Studio Code.

[Icenberg Snippets](https://marketplace.visualstudio.com/items?itemName=coderjerk.icenberg-snippets&ssr=false#overview)
