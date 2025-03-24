<?php

use MVRK\Icenberg\Icenberg;

$ice = new Icenberg('tests');

$ice::wrap(
    [
        $ice->get_element('test_text, options'),
        $ice->get_element('test_textarea, options'),
        $ice->get_element('test_number, options'),
        $ice->get_element('test_range, options'),
        $ice->get_element('test_wysiwyg, options'),
        $ice->get_element('test_email, options'),
        $ice->get_element('test_url, options'),
        $ice->get_element('test_password, options'), // private, returns nothing
        $ice->get_element('test_image, options'),
        $ice->get_element('test_file_array, options'),
        $ice->get_element('test_file_url, options'),
        $ice->get_element('test_file_id, options'),
        $ice->get_element('test_post_object, options'),
        $ice->get_element('test_post_object_multiple, options'),
        $ice->get_element('test_page_link_multiple, options'),
        $ice->get_element('test_page_link_single, options'),
        $ice->get_element('test_relationship, options'),
        $ice->get_element('test_form, options'),
        $ice->get_element('test_google_map, options'),
    ]
);
