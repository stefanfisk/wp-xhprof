<?php

class SF_XHProfLoader {
    private $started = false;

    function xhprof_is_enabled()
    {
        return extension_loaded('xhprof');
    }

    function should_profile_current_request()
    {
        return $this->xhprof_is_enabled();
    }

    function flags()
    {
        return XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY;
    }

    function options()
    {
        return array(
            'ignored_functions' => array(
                'call_user_func',
                'call_user_func_array',
                'preg_replace_callback',
                'do_action',
                'apply_filters',
            )
        );
    }

    function start($flags = SFHXPROF_FLAGS)
    {
        if (!$this->xhprof_is_enabled())
        {
            error_log('Failed to start profiling, the xhprof is not loaded.');
            return;
        }

        xhprof_enable($this->flags(), $this->options());

        $this->started = true;
    }

    function is_started()
    {
        return $this->started;
    }

    function stop()
    {
        return xhprof_disable();
    }
}

$sf_xhprof_loader = new SF_XHProfLoader();

if ($sf_xhprof_loader->should_profile_current_request()) $sf_xhprof_loader->start();
