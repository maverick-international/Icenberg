# Icenberg 🥶

- [Icenberg 🥶](#icenberg-)
    - [What is it?](#what-is-it)
    - [Getting Started](#getting-started)
      - [Initialise](#initialise)
    - [Using with ACF Flexible Content blocks](#using-with-acf-flexible-content-blocks)
  - [Using in an ACF Gutenberg Block](#using-in-an-acf-gutenberg-block)
    - [Icenberg Methods](#icenberg-methods)
      - [`get_element($field_name, $tag = 'div')`](#get_elementfield_name-tag--div)
      - [`the_element($field_name, $tag = 'div')`](#the_elementfield_name-tag--div)
      - [Global Options](#global-options)
      - [`enclose()`](#enclose)
  - [Values](#values)
  - [Conditionals and manipulations](#conditionals-and-manipulations)
      - [`field($field_name)`](#fieldfield_name)
      - [`get(string $tag = 'div')`](#getstring-tag--div)
      - [`prune(array $exclusions)`](#prunearray-exclusions)
      - [`only(array $inclusions)`](#onlyarray-inclusions)
      - [`is($value)`](#isvalue)
      - [`lessThan($value)` and `greaterThan($value)`](#lessthanvalue-and-greaterthanvalue)
  - [Special Fields](#special-fields)
      - [Google Maps Field](#google-maps-field)
      - [`settings($field_name, $additional_classes)`](#settingsfield_name-additional_classes)
      - [`get_buttons($field_name)` and `the_buttons($field_name)`](#get_buttonsfield_name-and-the_buttonsfield_name)
  - [CLI](#cli)
    - [Available commands](#available-commands)
    - [Configurqation](#configurqation)
  - [Supported fields](#supported-fields)
      - [Full Support](#full-support)
      - [Third party fields:](#third-party-fields)
      - [Special Fields](#special-fields-1)



### What is it?

Icenberg is an attempt to clean up ACF Flexible content and ACF Gutenberg block templates which often involve a lot of repetition and logic tangled up in presentation (in true WordPress style). 

Using Icenberg's methods we can render acf fields complete with BEM classes and settings in a clean(er) object oriented fashion, while still allowing us to do things the old fashioned way if necessary. 

Icenberg requires ACF Pro and is primarily for internal use at Maverick, although it is easy to implement on any WordPress template using ACF fields. It includes a convenient CLI for generating ACF Gutenberg blocks.

It is designed to be used primarily with flexible content fields and ACF Gutenberg blocks but could also work within other scenarios, in theory.

Note: The `buttons` and `settings` methods rely on Maverick specific setups and are not intended for general use.


### Getting Started

Install via composer:

```bash
composer require mvrk/icenberg
```

make sure autoloading is set up in functions.php - something like:

```php

$composer_path = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/';

if (file_exists($composer_path)) {

    require_once $composer_path . 'autoload.php';
}

```
Make sure you have ACF Pro installed. The library also supports [ACF Gravity forms](https://wordpress.org/plugins/acf-gravityforms-add-on/) plugin.

#### Initialise

```php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg('block_name');

```

### Using with ACF Flexible Content blocks
The following example takes place inside ACF's the_row() - ie:

```php

if (have_rows('content_blocks', $id)) :

    while (have_rows('content_blocks', $id)) : the_row();

        get_template_part('inc/blocks/block_template');

    endwhile;

endif;
```
Initialise with ACFs `the_row_layout()` in your block template:

```php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg(get_row_layout());

$ice->the_element('quote');
$ice->the_element('attribution');
$ice->the_element('portrait');

```

## Using in an ACF Gutenberg Block

since v0.5.0 you can use Icenberg in an ACF gutenberg block, just pass the block title instead of the row layout. You can use the wrap method in a gutenberg block to wrap the block frontend in a similar way to how it is wrapped automatically by wp in the backend:

```php
use MVRK\Icenberg\Icenberg;

$icenberg = new Icenberg(strtolower($block['title']));

$icenberg::wrap(
    [
        $icenberg->get_element('quote'),
        $icenberg->get_element('attribution'),
        $icenberg->get_element('portrait'),
    ],
    $block,
    true
);

```

### Icenberg Methods

#### `get_element($field_name, $tag = 'div')`

Returns an ACF field as a formatted string, wrapped up in all the divs you need and with any  special considerations applied. Takes the field name as an argument and optionally a tag for the uppermost element. If no tag is set it will use 'div'

```php

$field_name = $ice->get_element('field_name');

echo $field_name;

```

#### `the_element($field_name, $tag = 'div')`

As above, but echoes it out immediately.

```php

$ice->the_element('field_name');

```
Icenberg is smart enough to know what a field's type is, so you don't need to differentiate, you just pass the field name in.

#### Global Options

To retrieve a global option simply pass a comma delimeted string to `the_element()` or `get_element()` - it must be all as one string as opposed to seperate args and it must be comma delimited, where the first part is the field name and the second part is the options name, eg

```php

$ice->the_element('field_name, options');

```


#### `enclose()`

Enclose is a utility for wrapping multiple icenberg fields in a container div without having to use a ?> anywhere. You just need to pass it a classname (without prefixes as these will be applied by icenberg). So clean!

So for example, in a 'Cta' block,  where cta_heading is a text field and cta_content is a wysiwyg field:

```php

$ice->enclose('text', [
    $ice->get_element('cta_heading')
    $ice->get_element('cta_content'),
]);

```
will generate:

```html

<div class="block block--cta">
    <div class="section__inner">
        <div class="wrapper block--cta__wrapper">
            <div class="block--cta__text">
                <div class="block--cta__cta-heading">
                    I'm a heading, look at me!
                </div>
                <div class="block--cta__cta-content">
                    <p>
                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


```

You could also pass any thing else you like to enclose as part of the array, as long as its storable as a variable (for example inserting a `get_template_part()` won't work here because it effectively prints the content).

```php

$random_text = "<span>I am some random text, isn't it wonderful?</span>";

$ice->enclose('text', [
    $ice->get_element('cta_heading'),
    $random_text
]);

```

Of course life is never simple, so you will most likely need more complex layouts, but icenberg doesn't mind. You can insert it in html if you want to.

```html

<div class="testimonial__corner-illustration_green_reversed">
    <?php if($motif_variation_5_purple === 'orange') :
    $ice->the_element('motif_blurple');
    endif; ?>
</div>

```
## Values
You can us the `value()` method to return the 'raw', value of a given field without checking for existence or specifiying if it is a sub field.

```php
$lady_in_red = $ice->value('dancing_with_me');
```
as this just returns the value there are no special considerations for individual field types.

## Conditionals and manipulations

#### `field($field_name)`

you can use icenberg to evaluate fields and do some more comlplex field manipualtion too, using the `field()` method in conjucntion with the below methods. `field()` takes the field name as an argument and returns the icenberg instance for method chaining.

 ```php
 $ice->field($field_name)
 ```
#### `get(string $tag = 'div')`
Returns the icenbergified field html (in the same way as get_element). Optionally pass a tag for the wrapper.

```php
$ice->field('saxaphone')->get()
```

#### `prune(array $exclusions)`

pass an array of field names to the prune method to remove them from a group or from a repeater row.

```php
$group = $ice->field('bad_singers')->prune(['chris_de_burgh', 'cliff_richard'])->get();

```

#### `only(array $inclusions)`

pass an array of field names to the only method to extract them from a group or set of repeater rows. 

```php
$group = $ice->field('great_singers')->only(['chris_de_burgh'])->get('marquee');

```

#### `is($value)`

returns true if the value of the field equals the argument passed to `is()`. You don't need to check for a fields existance before using these methods as they will do it for you and return `false` if they don't.

```php
 if ($ice->field('font_colour')->is('red')) :
    $ice->the_element('font_colour');
else :
    echo 'oh no';
endif;
```

#### `lessThan($value)` and `greaterThan($value)`

Self explanatory, both take an integer as an argument. Warning: If you use it on a non numeric field it will return false.

```php
if ($ice->field('range_test')->lessThan(51)) :
    $class = 'text_' . $ice->field('range_test')->field;
    $ice->enclose($class, [
        $ice->get_element('cta_content'),
        $ice->get_element('cta_image'),
    ]);
endif;

```

## Special Fields

#### Google Maps Field

For the Google Maps field to work properly on front and backends you will need an API key. Once you have the key, add it to icenberg.yml in your project and the frontend will work. To make it work in the backend, add the following to your functions.php (in this example the key is defined in wp-config.php)

```php
function acf_google_map_field($api)
{
    $api['key'] = GOOGLE_MAPS_API_KEY;

    return $api;
}
add_filter('acf/fields/google_map/api', 'acf_google_map_field');
```

#### `settings($field_name, $additional_classes)`

Pass in a field group of settings and optionally an array of manually set classes and it will attach them as CSS modifier classes. if you include a text field called 'unique_id' in your group icenberg will attach it as a id too.

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
"class='orange orange_padding_top_300 orange_skin_purple' id='cheese_board'"
```

Depending on the settings in your group.


#### `get_buttons($field_name)` and `the_buttons($field_name)`

Return a formatted group of buttons with a huge range of styles catered for - very Maverick specific.Expects our usual group of buttons format.

## CLI

you can now use the Icenberg CLI to rapidly bootstrap Icenberg blocks (since v0.5.0). This extends wp-cli so you need to have that installed and working first.

Add the below to your functions.php, after the autoload.

```php
$is_wp_cli = defined('WP_CLI') && WP_CLI;

if ($is_wp_cli) {
    \MVRK\Icenberg\Commands\Bootstrap::setup();
}
``` 

### Available commands

`wp icenberg block --block_name`

this bootstraps an ACF gutenberg block with all relevant files ready to go.

This assumes that your blocks are in a folder called 'blocks' in your template root directory but this is configurable via icenberg.yaml

Icenberg generates a folder per block within the blocks folder. This folder contains
- block.json
- <block_name>.php
- <block_name>.css

It will also register an empty field group ready for access via the ACF GUI.

`wp icenberg block --block_name --flexible`

this is similar to the above but creates just the php and scss files for flexible content blocks. 


### Configurqation
Config options are now available by placing an 'icenberg.yaml' file in your project's root directory. If this file doesn't exist or can't be parsed, icenberg will just go ahead and use its defaults.

Supported config options below with their default values:

```yaml
block_directory_name: 'blocks' #change the default block directory name
sass_path: 'src/sass/blocks' #specify a location for sass partials
google_maps_api_key: '<your key here>'

```

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
- Wysiwyg
- Relationship
- Post Object
- Page Link
- Google Maps

#### Third party fields:
- Forms
- Swatch

#### Special Fields
- Buttons
- Settings
