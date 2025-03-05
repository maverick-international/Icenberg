<?php

namespace MVRK\Icenberg\Fields;

class File extends Base
{
    public $field;

    public $layout;

    public $tag;

    public $name;

    public function getElement($field, $layout, $tag)
    {
        $this->field = $field;
        $this->layout = $layout;
        $this->tag = $tag;
        $this->name = $field['_name'];

        $file = self::icefield($this->name);

        if ($file['type'] === 'video') {
            return  $this->getVideo($file);
        }

        $wrapped = "<a class='block--{$this->unSnake($layout)}__{$this->unSnake($this->name)}' href='{$file['url']}'>{$file['title']}</a>";

        return $wrapped;
    }

    public function getVideo($video)
    {

        if (is_array($video)) {
            $content = "<video loop muted autoplay playsinline>
                             <source src='{$video['url']}' type='video/mp4' />
                        </video>";
        }
        $wrapped = $this->wrap($content, $this->name, $this->layout, $this->tag);

        return $wrapped;
    }
}
