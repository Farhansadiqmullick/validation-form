<?php

if(!defined('ABSPATH')) die(-1);

if(!class_exists('WP_LIST_TABLE')){
    require_once ('ABSPATH'. '/wp-admin/includes/class-wp-list-table.php');
}

class VFORM_DATA extends WP_LIST_TABLE{
    private $_items;

    function set_data($data){
        $this->_items = $data;
    }

    function get_columns(){
        return [
            'cb' => '<input type="checkbox">',
            'name' => __('Name', 'vdata'),
            'email' => __('Email', 'vdata'),
            'phone' => __('Phone', 'vdata'),
            'zipcode' => __('Zipcode', 'vdata'),
        ];

    }

    function get_sortable_columns(){
        return [
            'name' => ['name', true],
            'email' => ['email', true]
        ];
    }

    function column_cb($item){
        return "<input type='checkbox' value='{$item['id']}'>";
    }

    function column_default($item, $column_name){
        return $item[$column_name];
    }

    function prepare_items()
    {
        $this->_column_headers = array($this->get_columns(), [], []);
    }
}