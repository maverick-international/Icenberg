<?php

namespace MVRK\Icenberg\Fields;

class File extends Base
{
    public $field_object;

    public $icenberg;

    public $layout;

    public $tag;

    public $name;

    public function getElement($field_object, $icenberg, $tag, $post_id)
    {
        $this->field_object = $field_object;

        $this->icenberg = $icenberg;

        $this->layout = $icenberg->layout;

        $this->tag = $tag;

        $this->name = $field_object['_name'];

        /**
         * file by id is unsupported,
         * use array or url instead, array for best results.
         */
        if ($field_object['return_format'] === 'id') {
            return false;
        }

        $file = self::icefield($this->name, $post_id);

        if (is_array($file)) {
            if ($file['type'] === 'video') {
                return  $this->getVideo($file);
            }

            $wrapped = "<a class='{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($this->name)}' href='{$file['url']}'>{$file['title']}</a>";
        } else {
            $wrapped = "<a class='{$icenberg->prefix}{$this->unSnake($icenberg->layout)}__{$this->unSnake($this->name)}' href='{$file}'>{$file}</a>";
        }

        return $wrapped;
    }

    /**
     * Opinionated: assumes that a video added
     * by file is for muted autoplay
     *
     * @param array $video
     * @return void
     */
    public function getVideo($video)
    {
        $content = '';

        if (is_array($video)) {
            $content = "<video loop muted autoplay playsinline><source src='{$video['url']}' type='video/mp4' /></video>";
        }

        $wrapped = $this->wrap($content, $this->name, $this->icenberg, $this->tag);

        return $wrapped;
    }
}
