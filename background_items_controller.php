<?php

/**
 * Background_items_controller class
 *
 * @package background_items
 * @author tuxudo
 **/
class Background_items_controller extends Module_controller
{
    public function __construct()
    {
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the background_items module!";
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]]/", '', $serial_number);

        $queryobj = new Background_items_model();
        $background_items_tab = array();
        foreach($queryobj->retrieve_records($serial_number) as $shareEntry) {
            $background_items_tab[] = $shareEntry->rs;
        }

        $obj = new View();
        $obj->view('json', array('msg' => $background_items_tab));
    }
} // End class Background_items_controller
