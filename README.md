# Icenberg 🥶



- [Icenberg 🥶](#icenberg-)
    - [What is it?](#what-is-it)
    - [Getting Started](#getting-started)
    - [Icenberg Methods](#icenberg-methods)
      - [`get_element($field_name, $tag = 'div')`](#get_elementfield_name-tag--div)
      - [`the_element($field_name, $tag = 'div')`](#the_elementfield_name-tag--div)
      - [`enclose()`](#enclose)
      - [`settings($field_name, $additional_classes)`](#settingsfield_name-additional_classes)
  - [Conditionals](#conditionals)
      - [`field($field_name)`](#fieldfield_name)
      - [`is($value)`](#isvalue)
      - [`lessThan($value)` and `greaterThan($value)`](#lessthanvalue-and-greaterthanvalue)
  - [Maverick Specific](#maverick-specific)
      - [`get_buttons($field_name)` and `the_buttons($field_name)`](#get_buttonsfield_name-and-the_buttonsfield_name)
  - [Supported fields](#supported-fields)
      - [Currently Supported fields](#currently-supported-fields)
      - [Third party fields:](#third-party-fields)
      - [Special Fields](#special-fields)

### What is it?

This requires ACF Pro and is primarily for internal use at Maverick, although it is easy to implement on any WordPress template using ACF's Flexible content fields.

Icenberg is an attempt to clean up ACF Flexible content block templates which often involve a lot of repetition and logic tangled up in presentation, in true WordPress style.

Using Icenberg's methods we can render any acf fields complete with BEM classes and settings in a clean(er) OO fashion, while still allowing us to do things the old fashioned way if necessary.

It is designed to be used primarily with flexible content fields as it asumes the existance of 'the row' but could also work within non-flexible groups and repeaters, in theory.

Note: The `buttons` and `settings` methods rely on Maverick specific setups, we'll make these more generally usable in the future.


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

The following all takes place inside ACF's the_row() - ie:
```php

if (have_rows('content_blocks', $id)) :

    while (have_rows('content_blocks', $id)) : the_row();

        get_template_part('inc/blocks/block_template');

    endwhile;

endif;
```
Initialise with ACFs `the_row_layout()`

```php

use MVRK\Icenberg\Icenberg;

$icenberg = new Icenberg(get_row_layout());

```
Once that's intialised you're ready to build your block.

### Icenberg Methods

#### `get_element($field_name, $tag = 'div')`

Returns an ACF field as a formatted string, wrapped up in all the divs you need and with any  special considerations applied. Takes the field name as an argument and optionally a tag for the uppermost element. If no tag is set it will use 'div'

```php

$field_name = $icenberg->get_element('field_name');

echo $field_name;

```

#### `the_element($field_name, $tag = 'div')`

As above, but echoes it out immediately.

```php

$icenberg->the_element('field_name');

```
Icenberg is smart enough to know what a field's type is, so you don't need to differentiate, you just pass the field name in.


#### `enclose()`

Enclose is a utility for wrapping multiple icenberg fields in a container div without having to use a ?> anywhere. You just need to pass it a classname (without prefixes as these will be applied by icenberg). So clean!

So for example, in a 'Cta' block,  where cta_heading is a text field and cta_content is a wysiwyg field:

```php

$icenberg->enclose('text', [
    $icenberg->get_element('cta_heading')
    $icenberg->get_element('cta_content'),
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

$icenberg->enclose('text', [
    $icenberg->get_element('cta_heading'),
    $random_text
]);

```

Of course life is never simple, so you will most likely need more complex layouts, but icenberg doesn't mind. You can insert it in html if you want to.

```html

<div class="testimonial__corner-illustration_green_reversed">
    <?php if($motif_variation_5_purple === 'orange') :
    $icenberg->the_element('motif_blurple');
    endif; ?>
</div>

```

#### `settings($field_name, $additional_classes)`

Pass in a field group of settings and optionally an array of manually set classes and it will attach them as CSS modifier classes. if you include a text field called 'unique_id' in your group icenberg will attach it as a id too.

Example using settings in  `enclose()`:

```php
$classes = ['banana', 'orange'];

$block_settings = get_sub_field($block_settings);

$settings = $icenberg->settings($block_settings, $classes);

$icenberg->enclose ($settings, [
    $icenberg->get_element('cheese_board'),
    $icenberg->get_element('flame_thrower')
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

## Conditionals

#### `field($field_name)`

you can use icenberg to evaluate fields too, using the `field()` method in conjucntion with the below methods. `field()` takes the field name as an argument and returns the icenberg instance for method chaining.

 ```php
 $icenberg->field($field_name)
 ```

#### `is($value)`

returns true if the value of the field equals the argument to `is()`. You don't need to check for a fields existance before using these methods as they will do it for you and return `false` if they don't.

```php
 if ($icenberg->field('font_colour')->is('red')) :
    $icenberg->the_element('font_colour');
else :
    echo 'oh no';
endif;
```

#### `lessThan($value)` and `greaterThan($value)`

Self explanatory, both take an integer as an argument. Warning: If you use it on a non numeric field it will return false.

```php
if ($icenberg->field('range_test')->lessThan(51)) :
    $class = 'text_' . $icenberg->field('range_test')->field;
    $icenberg->enclose($class, [
        $icenberg->get_element('cta_content'),
        $icenberg->get_element('cta_image'),
    ]);
endif;
```


## Maverick Specific

#### `get_buttons($field_name)` and `the_buttons($field_name)`

Return a formatted button group with a huge range of styles catered for - very Maverick specific.
Expects our usual button group format.


## Supported fields

#### Currently Supported fields
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

#### Third party fields:
- Forms
- Swatch

#### Special Fields
- Buttons
- Settings
