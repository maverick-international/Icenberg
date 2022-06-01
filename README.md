# Icenberg 🥶

### What is it?

Icenberg is an attempt to clean up our block templates which involve a lot of repetition and a lot of logic tangled up in presentation, in true WordPress style.

Using Icenberg's methods we can render any acf fields complete with classes and settings in a clean(er) fashion, while still allowing us to do things the old fashioned way if necessary.

This will lead to more readable code and a smoother block creation experience, making creating 10 different types of testimonial a breeze.

It is designed to be used primarily with flexible content fields as it asumes the existance of 'the row' but could also work within non-flexible groups and repeaters, in theory.

Icenberg works in conjunction with MVRK CLI which will generate the boilerplate for you.

Currently destined to sit in the cookiecutter theme, I would hope to move it to an (open source) composer package soon. Or maybe a plugin (yuck).

Its a little known fact that the theme inc/classes folder is namespaced to MVRK (hidden away in composer.json). So as long as composer autoloading is enabled in functions.php we can use Icenberg anywhere in the theme.

## How it works

### page.php

Instead of dynamically grabbing partials within page.php as we traditionally have done, we now pass the row to a block template which performs the initial wrapping of the block with settings, a section__inner and a wrapper.


```php

if (have_rows('content_blocks', $id)) :

    while (have_rows('content_blocks', $id)) : the_row();

        get_template_part('inc/blocks/block_template');

    endwhile;

endif;

```

### inc/blocks/block_template.php

`block--` is prepended to the block name when creating classes to allow for clean overrides on individual blocks.

So for instance if your flexible block is called 'general_content' in acf, once you send the row through the base block template it will be set up as

```html
<div class="block block--general_content">
    <div class="section__inner">
        <div class="wrapper block--general_content__wrapper">

        {{ individual block inserted here }}

        </div>
    </div>
</div>
```

So then in your block file you can just concentrate on the inner part of the block.
Settings are applied at the base template level so you don't need to worry about them, but they'll still be available to you in the block template if you need them there.

### inc/blocks/your_block_name.php

So now, having done the setup all that needs to be added to the individual block template is the actual meat of the block. In a very simple block setup you will now be able to render everything without writing any html whatsoever!! You just need to use the Icenberg methods which hide all the field structure.

At the head of the file you need to set up a new icenberg instance, passing the layout name as a string (acf's get_row_layout() gives us that):

```php

use MVRK\Icenberg\Icenberg;

$icenberg = new Icenberg(get_row_layout());

```
Once that's intialised you're ready to build your block.

### Icenberg Methods

#### `get_element()`

Returns an ACF field as a formatted string, wrapped up in all the divs you need and with any  special considerations applied.

```php

$field_name = $icenberg->get_element('field_name');

echo $field_name;

```

#### `the_element()`

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
will spit out:

```html

<div class="block block--cta">
    <div class="section__inner">
        <div class="wrapper block--cta__wrapper">
            <div class="block--cta__text">
                <div class="block--cta__cta_heading">
                    I'm a heading, look at me!
                </div>
                <div class="block--cta__cta_content">
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
## Conditionals

```php
if ($icenberg->field('range_test')->is('50')) :
    $icenberg->the_element('range_test');
else :
    echo 'oh no';
endif;

if ($icenberg->field('range_test')->lessThan(51)) :
    $class = 'text_' . $icenberg->field('range_test')->field;
    $icenberg->enclose($class, [
        $icenberg->get_element('cta_content'),
        $icenberg->get_element('cta_image'),
    ]);
endif;
```
