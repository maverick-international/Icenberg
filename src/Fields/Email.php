<?php

namespace MVRK\Icenberg\Fields;

use MVRK\Icenberg\Icenberg;

/**
 * Email featuring spam protection.
 *
 * @link https://www.advancedcustomfields.com/resources/email/
 * @link https://developer.wordpress.org/reference/functions/antispambot/
 */
class Email extends Field
{
    public function getElement(mixed $field_object, string $tag, mixed $post_id, string $field_classes, string $base_class, Icenberg $icenberg): string
    {
        $name = $field_object['_name'];
        $content = self::icefield($name, $post_id);
        $hexed_email = $this->antiSpamBot($content, 1);
        $email = $this->antiSpamBot($content, 0);

        return "<a class='{$field_classes}' href='mailto:$hexed_email'>{$email}</a>";
    }

    /**
     * Copied from antispambot function in WP
     *
     * @param string $email_address
     * @param int    $hex_encoding
     *
     * @return array|string
     */
    protected function antiSpamBot(string $email_address, int $hex_encoding = 0): array|string
    {
        $email_no_spam_address = '';

        for ($i = 0, $len = strlen($email_address); $i < $len; $i++) {
            $j = rand(0, 1 + $hex_encoding);

            if (0 === $j) {
                $email_no_spam_address .= '&#' . ord($email_address[$i]) . ';';
            } elseif (1 === $j) {
                $email_no_spam_address .= $email_address[$i];
            } elseif (2 === $j) {
                $email_no_spam_address .= '%' . zeroise(dechex(ord($email_address[$i])), 2);
            }
        }

        return str_replace('@', '&#64;', $email_no_spam_address);
    }
}
