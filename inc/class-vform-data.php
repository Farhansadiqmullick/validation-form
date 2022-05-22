<?php

if(!defined('ABSPATH')) die(-1);

if(!class_exists('WP_LIST_TABLE')){
    require_once ('ABSPATH'. '/wp-admin/includes/class-wp-list-table.php');
}

class VFORM_DATA extends WP_LIST_TABLE{

     private $_items;
    // function __construct($data)
    // {
    //     parent::__construct();
    //     $this->items = $data;
    // }

    function get_columns(){
        return [
            'cb' => '<input type="checkbox">',
            'name' => __('Name', 'vdata'),
            'email' => __('Email', 'vdata'),
            'phone' => __('Phone', 'vdata'),
            'zipcode' => __('Zipcode', 'vdata'),
        ];

    }

    function set_data($data){
        parent::__construct();
     $this->_items = $data;
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
        $paged = $_REQUEST['paged']?? 1;
        $per_page = 2;
        $total_items = count($this->_items);
        $this->_column_headers = array($this->get_columns(), [], $this->get_sortable_columns());
        $data_chunk = array_chunk($this->_items,$per_page);
        if(count($data_chunk) > 0){
        $this->items = $data_chunk[$paged - 1];
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_page' => count($this->items)/ $per_page,
        ]);
        }else{
            echo 'No Matching Name Found';
        }
    }
}