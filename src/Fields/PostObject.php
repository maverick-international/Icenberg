<?php

namespace MVRK\Icenberg\Fields;


class PostObject extends Base
{

    /**
     * Highly opinionated handling of post object field.
     *
     * @param string $field
     * @param string $layout
     * @param string $tag
     * @return void
     */
    public function getElement($field, $layout, $tag)
    {
        $name = $field['_name'];

        $post_object_field = get_sub_field($name);

        if (!$post_object_field) {
            return;
        }

        // if the field is set to only return an ID then just return the ID
        if (is_int($post_object_field)) {
            return $post_object_field;
        }

        // if multiple posts have been enabled
        if (is_array($post_object_field)) {
            $content = '';
            foreach ($post_object_field as $post_object) {
                $content .=  $this->buildPostContent($post_object, $name, $layout, $tag);
            }
        }

        // field using default behaviour and returning a single post object
        if (is_object($post_object_field)) {
            $content =  $this->buildPostContent($post_object_field, $name, $layout, $tag);
        }

        return $this->wrap($content, $name, $layout, $tag);
    }


    protected function buildPostContent($post_object, $name, $layout, $tag)
    {
        $content = [];

        $content['title'] = $post_object->post_title;

        $content['modified'] = $post_object->post_modified;

        $content['excerpt'] = $post_object->post_excerpt;

        $content['link'] = $post_object->guid;

        $date = new \DateTime($content['modified']);

        $post_content = $this->wrap($date->format('jS F Y'), $name . '__date', $layout, $tag);

        $post_content .=  $this->wrap($content['title'], $name . '__title', $layout, 'h4');

        $post_content .=  $this->wrap($content['excerpt'], $name . '__excerpt', $layout, 'p');

        $post_content .= "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$content['link']}'>Read More</a>";

        $post_wrap = $this->wrap($post_content, $name . '__item', $layout, $tag);

        $linked_wrap = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($name)}' href='{$content['link']}'>{$post_wrap}</a>";

        dump($linked_wrap);

        return $linked_wrap;
    }
}
