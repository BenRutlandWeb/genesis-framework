<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($proxy = apply_filters('genesis.proxy', '')) {
    echo view($proxy);
}
remove_all_filters('genesis.proxy');
