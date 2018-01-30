<?php

namespace block_quickmail\requests;

class signature_request extends \block_quickmail_request {
    
    /**
     * Reports whether or not the request was submitted with intent to save
     * 
     * @return bool
     */
    public function to_save_signature() {
        return $this->was_submitted('save');
    }

    /**
     * Reports whether or not the request was submitted with intent to delete
     * 
     * @return bool
     */
    public function to_delete_signature() {
        return $this->has_form_data_matching('delete_signature_flag', 1);
    }

    public static function get_transformed_post_data($form_data)
    {
        $transformed_post = (object)[];
        
        $transformed_post->subject = self::get_transformed_subject($form_data);
        $transformed_post->message = self::get_transformed_message_body($form_data);
        $transformed_post->mailto_ids = self::get_transformed_mailto_ids($form_data);
        $transformed_post->additional_emails = self::get_transformed_additional_emails($form_data);
        $transformed_post->signature_id = self::get_transformed_signature_id($form_data);
        $transformed_post->message_type = self::get_transformed_message_type($form_data);
        $transformed_post->receipt = self::get_transformed_receipt($form_data);
        $transformed_post->alternate_email_id = self::get_transformed_alternate_email_id($form_data);
        $transformed_post->to_send_at = self::get_transformed_to_send_at($form_data);
        $transformed_post->attachments_draftitem_id = self::get_transformed_attachments_draftitem_id($form_data);
        $transformed_post->no_reply = self::get_transformed_no_reply($form_data);

        return $transformed_post;
    }

    /**
     * Returns a sanitized subject from the given form post data
     * 
     * @param  array  $form_data
     * @return string
     */
    public static function get_transformed_subject($form_data)
    {
        return (string) $form_data->subject;
    }

    /**
     * Returns a sanitized message body from the given form post data
     * 
     * @param  array  $form_data
     * @return string
     */
    public static function get_transformed_message_body($form_data)
    {
        return (string) $form_data->message_editor['text'];
    }

    /**
     * Returns a sanitized array of recipient user ids from the given form post data
     * 
     * @param  array  $form_data
     * @return array
     */
    public static function get_transformed_mailto_ids($form_data)
    {
        return empty($form_data->mailto_ids) ? [] : explode(',', rtrim($form_data->mailto_ids, ','));
    }

    /**
     * Returns a sanitized array of additional emails from the given form post data
     * 
     * @param  array  $form_data
     * @return array
     */
    public static function get_transformed_additional_emails($form_data)
    {
        $additional_emails = $form_data->additional_emails;

        $emails = ! empty($additional_emails) ? array_unique(explode(',', $additional_emails)) : [];

        // eliminate any white space
        $emails = array_map(function($email) {
            return trim($email);
        }, $emails);

        // return all valid emails
        return array_filter($emails, function($email) {
            return strlen($email) > 0;
        });
    }

    /**
     * Returns a sanitized signature id from the given form post data
     * 
     * @param  array  $form_data
     * @return int
     */
    public static function get_transformed_signature_id($form_data)
    {
        return ! $form_data->signature_id ? 0 : (int) $form_data->signature_id;
    }

    /**
     * Returns a sanitized message type from the given form post data
     * 
     * @param  array  $form_data
     * @return string
     */
    public static function get_transformed_message_type($form_data)
    {
        return ! empty($form_data->message_type) ? (string) $form_data->message_type : block_quickmail_config::_c('default_message_type');
    }

    /**
     * Returns a sanitized receipt value from the given form post data
     * 
     * @param  array  $form_data
     * @return bool
     */
    public static function get_transformed_receipt($form_data)
    {
        return (bool) $form_data->receipt;
    }

    /**
     * Returns a sanitized alternate email id from the given form post data
     * 
     * @param  array  $form_data
     * @return int
     */
    public static function get_transformed_alternate_email_id($form_data)
    {
        return (int) $form_data->from_email_id > 0
            ? $form_data->from_email_id
            : 0;
    }

    /**
     * Returns a sanitized to send at timestamp from the given form post data
     * 
     * @param  array  $form_data
     * @return int
     */
    public static function get_transformed_to_send_at($form_data)
    {
        return ! $form_data->to_send_at ? 0 : (int) $form_data->to_send_at;
    }

    /**
     * Returns a sanitized no_reply value from the given form post data
     * 
     * @param  array  $form_data
     * @return bool
     */
    public static function get_transformed_no_reply($form_data)
    {
        return $form_data->from_email_id == -1
            ? true
            : false;
    }

    /**
     * Returns ...
     * 
     * @param  array  $form_data
     * @return int
     */
    public static function get_transformed_attachments_draftitem_id($form_data)
    {
        return ! $form_data->attachments ? 0 : (int) $form_data->attachments;
    }


    // redirect_back_to_course_after_send
    
    // redirect_back_to_course_after_save
    
}
