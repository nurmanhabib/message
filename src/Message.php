<?php namespace Nurmanhabib\Message;

use Session;
use Button as Btn;

class Message {

    protected $message = array(
        'success'   => array(),
        'error'     => array(),
        'warning'   => array(),
        'info'      => array(),
    );

    protected $options = array(
        'alert'     => array(
            'type'      => 'danger',
            'inline'    => true,
            'modal'     => false,
            'close'     => true,
        )
    );

    public function __construct()
    {
        $message    = $this->message;

        // Jika terdapat pesan error dari Validator
        if(Session::has('errors'))
        {
            $errors     = Session::get('errors');
            
            // Jika errors merupakan object MessageBag
            if(is_object($errors)) {
                $message['error']   = $errors->all();
            } else {
                $message['error']   = $errors;
            }
        }

        // Jika terdapat pesan success dari Session
        if(Session::has('success'))
        {
            $message['success']   = Session::get('success');
        }

        // Jika terdapat pesan error dari Session
        if(Session::has('error'))
        {
            $message['error']   = Session::get('error');
        }

        // Jika terdapat pesan warning dari Session
        if(Session::has('warning'))
        {
            $message['warning']   = Session::get('warning');
        }

        // Jika terdapat pesan info dari Session
        if(Session::has('info'))
        {
            $message['info']   = Session::get('info');
        }

        $this->message  = $message;
    }

    private function generate_attr($attributes, $first_spacing = true)
    {
        $html   = '';

        foreach ($attributes as $attribute => $value)
        {
            if($html != '' || $first_spacing)
                $html   .= ' ';
                $html   .= $attribute . '="';

                if(!is_array($value))
                    $html   .= $value;

                $html   .= '"';
        }

        return $html;
    }

    public function alert($text = '', $options = array(), $attr = array())
    {
        $options        = empty($options) ? array() : $options;
        $attr           = empty($attr) ? array() : $attr;
        $local          = array_merge($this->options['alert'], $options);

        $type       = $local['type'] == 'error' ? 'danger' : $local['type'];
        $close      = $local['close'];
        $close_btn  = Btn::button('&times;', array(), array('class' => 'close', 'data-dismiss' => 'alert', 'aria-hidden' => 'true'));

        $class  = 'alert';
        $class  .= ' alert-' . $type;

        // Merge class to attribute
        $attr['class']  = !array_key_exists('class', $attr) ? $class : $attr['class'];

        $html   = '<div' . $this->generate_attr($attr) . '>';
        $html   .= $close ? $close_btn : '';

        if(is_array($text))
            foreach ($text as $msg)
                $html   .= '<p>' . $msg . '<p>';
        else
            $html   .= $text;

        $html   .= '</div>';

        return $html;
    }

    public function set($type, $message)
    {
        $this->message[$type] = $message;

        return $this;
    }

    public function success($message)
    {
        return $this->set('success', $message);
    }

    public function error($message)
    {
        return $this->set('danger', $message);
    }

    public function warning($message)
    {
        return $this->set('warning', $message);
    }

    public function danger($message)
    {
        return $this->set('danger', $message);
    }

    public function info($message)
    {
        return $this->set('info', $message);
    }

    public function show($type = '')
    {        
        $message    = $this->message;
        $html       = '';

        if (!empty($type))
            $html   .= !empty($message[$type]) ? $this->alert($message[$type], array('type' => $type)) : '';
        else
            foreach ($message as $type => $text)
                $html   .= !empty($text) ? $this->alert($text, array('type' => $type)) : '';

        return $html;
    }

}