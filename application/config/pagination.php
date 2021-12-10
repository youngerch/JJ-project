<?php

$config['uri_segment']      = 3;
$config['use_page_numbers'] = true;

$config['full_tag_open']    = '<ul class="pagination">';
$config['full_tag_close']   = '</ul>';

$config['first_link']       = false;
$config['first_tag_open']   = '<li class="page-item">';
$config['first_tag_close']  = '</li>';

$config['last_link']        = false;
$config['last_tag_open']    = '<li class="page-item">';
$config['last_tag_close']   = '</li>';

$config['next_link']        = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
$config['next_tag_open']    = '<li class="page-item">';
$config['next_tag_close']   = '</li>';

$config['prev_link']        = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
$config['prev_tag_open']    = '<li class="page-item">';
$config['prev_tag_close']   = '</li>';

$config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#link">';
$config['cur_tag_close']    = '</a></li>';

$config['num_tag_open']     = '<li class="page-item">';
$config['num_tag_close']    = '</li>';
$config['attributes']       = array('class' => 'page-link');