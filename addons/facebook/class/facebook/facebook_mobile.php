<?php
// Copyright 2004-present Facebook. All Rights Reserved.
//
// +---------------------------------------------------------------------------+
// | Facebook Platform PHP5 client                                             |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2007 Facebook, Inc.                                         |
// | All rights reserved.                                                      |
// |                                                                           |
// | Redistribution and use in source and binary forms, with or without        |
// | modification, are permitted provided that the following conditions        |
// | are met:                                                                  |
// |                                                                           |
// | 1. Redistributions of source code must retain the above copyright         |
// |    notice, this list of conditions and the following disclaimer.          |
// | 2. Redistributions in binary form must reproduce the above copyright      |
// |    notice, this list of conditions and the following disclaimer in the    |
// |    documentation and/or other materials provided with the distribution.   |
// |                                                                           |
// | THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR      |
// | IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES |
// | OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.   |
// | IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,          |
// | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT  |
// | NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY     |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT       |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF  |
// | THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.         |
// +---------------------------------------------------------------------------+
// | For help with this library, contact developers-help@facebook.com          |
// +---------------------------------------------------------------------------+
//

/**
 *  This class extends and modifies the "Facebook" class to better suit wap
 *  apps. Since there is no javascript support, we need to use server redirect
 *  to implement Facebook connect functionalities such as authenticate,
 *  authorize, feed form etc.. This library provide many helper functions for
 *  wap developer to locate the right wap url. The url here is targed at
 *  facebook wap site or wap-friendly url.
 */
class FacebookMobile extends Facebook
{
    // the application secret, which differs from the session secret

    /**
     * FacebookMobile constructor.
     * @param array $api_key
     * @param       $secret
     * @param bool  $generate_session_secret
     */
    public function __construct($api_key, $secret, $generate_session_secret = false)
    {
        parent::__construct($api_key, $secret, $generate_session_secret);
    }

    /**
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    /**
     * @param $action
     * @param $params
     * @return string
     */
    public function get_m_url($action, $params)
    {
        $page = parent::get_facebook_url('m') . '/' . $action;
        foreach ($params as $key => $val) {
            if (!$val) {
                unset($params[$key]);
            }
        }

        return $page . '?' . http_build_query($params);
    }

    /**
     * @param $action
     * @param $params
     * @return string
     */
    public function get_www_url($action, $params)
    {
        $page = parent::get_facebook_url('www') . '/' . $action;
        foreach ($params as $key => $val) {
            if (!$val) {
                unset($params[$key]);
            }
        }

        return $page . '?' . http_build_query($params);
    }

    /**
     * @param null $next
     * @return string
     */
    public function get_add_url($next = null)
    {
        return $this->get_m_url('add.php', [
            'api_key' => $this->api_key,
            'next'    => $next
        ]);
    }

    /**
     * @param null $next
     * @param null $cancel
     * @param null $canvas
     * @return string
     */
    public function get_tos_url($next = null, $cancel = null, $canvas = null)
    {
        return $this->get_m_url('tos.php', [
            'api_key' => $this->api_key,
            'v'       => '1.0',
            'next'    => $next,
            'canvas'  => $canvas,
            'cancel'  => $cancel
        ]);
    }

    /**
     * @param null $next
     * @return string
     */
    public function get_logout_url($next = null)
    {
        $params = [
            'api_key'     => $this->api_key,
            'session_key' => $this->api_client->session_key,
        ];

        if ($next) {
            $params['connect_next'] = 1;
            $params['next']         = $next;
        }

        return $this->get_m_url('logout.php', $params);
    }

    /**
     * @param null $next
     * @param null $cancel_url
     * @return string
     */
    public function get_register_url($next = null, $cancel_url = null)
    {
        return $this->get_m_url('r.php', [
            'fbconnect'  => 1,
            'api_key'    => $this->api_key,
            'next'       => $next ?: parent::current_url(),
            'cancel_url' => $cancel_url ?: parent::current_url()
        ]);
    }

    /**
     * These set of fbconnect style url redirect back to the application current
     * page when the action is done. Developer can also use the non fbconnect
     * style url and provide their own redirect link by giving the right parameter
     * to $next and/or $cancel_url
     */
    public function get_fbconnect_register_url()
    {
        return $this->get_register_url(parent::current_url(), parent::current_url());
    }

    /**
     * @return string
     */
    public function get_fbconnect_tos_url()
    {
        return $this->get_tos_url(parent::current_url(), parent::current_url(), $this->in_frame());
    }

    /**
     * @return string
     */
    public function get_fbconnect_logout_url()
    {
        return $this->get_logout_url(parent::current_url());
    }

    public function logout_user()
    {
        $this->user = null;
    }

    /**
     * @param      $ext_perm
     * @param null $next
     * @param null $cancel_url
     * @return string
     */
    public function get_prompt_permissions_url($ext_perm, $next = null, $cancel_url = null)
    {
        return $this->get_www_url('connect/prompt_permissions.php', [
            'api_key'  => $this->api_key,
            'ext_perm' => $ext_perm,
            'next'     => $next ?: parent::current_url(),
            'cancel'   => $cancel_url ?: parent::current_url(),
            'display'  => 'wap'
        ]);
    }

    /**
     * support both prompt_permissions.php and authorize.php for now.
     * authorized.php is to be deprecate though.
     * @param      $ext_perm
     * @param null $next
     * @param null $cancel_url
     * @return string
     */
    public function get_extended_permission_url($ext_perm, $next = null, $cancel_url = null)
    {
        $next       = $next ?: parent::current_url();
        $cancel_url = $cancel_url ?: parent::current_url();

        return $this->get_m_url('authorize.php', [
            'api_key'    => $this->api_key,
            'ext_perm'   => $ext_perm,
            'next'       => $next,
            'cancel_url' => $cancel_url
        ]);
    }

    /**
     * @param null   $action_links
     * @param null   $target_id
     * @param string $message
     * @param string $user_message_prompt
     * @param null   $caption
     * @param string $callback
     * @param string $cancel
     * @param null   $attachment
     * @param bool   $preview
     */
    public function render_prompt_feed_url(
        $action_links = null,
        $target_id = null,
        $message = '',
        $user_message_prompt = '',
        $caption = null,
        $callback = '',
        $cancel = '',
        $attachment = null,
        $preview = true
    ) {
        $params = [
            'api_key'     => $this->api_key,
            'session_key' => $this->api_client->session_key,
        ];
        if (!empty($attachment)) {
            $params['attachment'] = urlencode(json_encode($attachment));
        } else {
            $attachment              = new stdClass();
            $app_display_info        = $this->api_client->admin_getAppProperties([
                                                                                     'application_name',
                                                                                     'callback_url',
                                                                                     'description',
                                                                                     'logo_url'
                                                                                 ]);
            $app_display_info        = $app_display_info;
            $attachment->name        = $app_display_info['application_name'];
            $attachment->caption     = !empty($caption) ? $caption : 'Just see what\'s new!';
            $attachment->description = $app_display_info['description'];
            $attachment->href        = $app_display_info['callback_url'];
            if (!empty($app_display_info['logo_url'])) {
                $logo              = new stdClass();
                $logo->type        = 'image';
                $logo->src         = $app_display_info['logo_url'];
                $logo->href        = $app_display_info['callback_url'];
                $attachment->media = [$logo];
            }
            $params['attachment'] = urlencode(json_encode($attachment));
        }
        $params['preview']             = $preview;
        $params['message']             = $message;
        $params['user_message_prompt'] = $user_message_prompt;
        if (!empty($callback)) {
            $params['callback'] = $callback;
        } else {
            $params['callback'] = static::current_url();
        }
        if (!empty($cancel)) {
            $params['cancel'] = $cancel;
        } else {
            $params['cancel'] = static::current_url();
        }

        if (!empty($target_id)) {
            $params['target_id'] = $target_id;
        }
        if (!empty($action_links)) {
            $params['action_links'] = urlencode(json_encode($action_links));
        }

        $params['display'] = 'wap';
        header('Location: ' . $this->get_www_url('connect/prompt_feed.php', $params));
    }

    //use template_id

    /**
     * @param null $template_id
     * @param null $template_data
     * @param null $user_message
     * @param null $body_general
     * @param null $user_message_prompt
     * @param null $target_id
     * @param null $callback
     * @param null $cancel
     * @param bool $preview
     */
    public function render_feed_form_url(
        $template_id = null,
        $template_data = null,
        $user_message = null,
        $body_general = null,
        $user_message_prompt = null,
        $target_id = null,
        $callback = null,
        $cancel = null,
        $preview = true
    ) {
        $params            = ['api_key' => $this->api_key];
        $params['preview'] = $preview;
        if (isset($template_id) && $template_id) {
            $params['template_id'] = $template_id;
        }
        $params['message'] = $user_message ? $user_message['value'] : '';
        if (isset($body_general) && $body_general) {
            $params['body_general'] = $body_general;
        }
        if (isset($user_message_prompt) && $user_message_prompt) {
            $params['user_message_prompt'] = $user_message_prompt;
        }
        if (isset($callback) && $callback) {
            $params['callback'] = $callback;
        } else {
            $params['callback'] = static::current_url();
        }
        if (isset($cancel) && $cancel) {
            $params['cancel'] = $cancel;
        } else {
            $params['cancel'] = static::current_url();
        }
        if (isset($template_data) && $template_data) {
            $params['template_data'] = $template_data;
        }
        if (isset($target_id) && $target_id) {
            $params['to_ids'] = $target_id;
        }
        $params['display'] = 'wap';
        header('Location: ' . $this->get_www_url('connect/prompt_feed.php', $params));
    }
}
