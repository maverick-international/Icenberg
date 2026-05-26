<?php

namespace MVRK\Icenberg;

use MVRK\Icenberg\Utils\Format;

class Settings
{
    public array $settings;
    public ?array $classes;
    protected ?string $class_string;
    public ?string $prefix;

    public function __construct(array $settings, ?array $classes = [], ?string $prefix = 'block')
    {
        $this->settings = $settings;
        $this->classes = $classes;
        $this->prefix = $prefix;
    }

    public function applySettings(): string
    {
        $this->setClasses();
        return "{$this->class_string}";
    }

    protected function setClasses(): void
    {
        if ($this->settings) {
            foreach ($this->settings as $setting_group) {
                foreach ($setting_group as $key => $value) {
                    $this->parseFields($key, $value);
                }
            }
        }

        $class_list = strtolower(implode(' ', $this->classes));

        $this->class_string = "class='{$class_list}' ";
    }

    protected function parseFields(string $key, mixed $value): void
    {

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        if (is_numeric($value)) {
            $value = strval($value);
        }

        if (!is_iterable($value)) {
            $value = strval($value);

            if ($value) {
                $class = "{$this->prefix}--{$key}-{$value}";
                $this->classes[] = Format::kebabCase($class);
            }
        } elseif (is_array($value)) {
            foreach ($value as $k => $v) {
                if (!is_iterable($v)) {
                    if (is_bool($v)) {
                        $v = $v ? 'true' : 'false';
                    }
                    if (is_numeric($v)) {
                        $v = strval($v);
                    }
                    if (isset($v)) {
                        $class = "{$this->prefix}--{$key}-{$k}-{$v}";
                        $this->classes[] = Format::kebabCase($class);
                    }
                } else {
                    $this->parseFields($k, $v);
                }
            }
        }
    }
}
