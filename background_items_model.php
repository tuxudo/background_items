<?php

use CFPropertyList\CFPropertyList;

class Background_items_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'background_items'); // Primary key, tablename
        $this->rs['id'] = "";
        $this->rs['serial_number'] = $serial;
        $this->rs['user'] = null;
        $this->rs['btm_index'] = null;
        $this->rs['name'] = null;
        $this->rs['developer_name'] = null;
        $this->rs['url'] = null;
        $this->rs['team_id'] = null;
        $this->rs['assoc_bundle_id'] = null;
        $this->rs['type'] = null;
        $this->rs['identifier'] = null;
        $this->rs['generation'] = null;
        $this->rs['parent_id'] = null;
        $this->rs['executable_path'] = null;
        $this->rs['state_enabled'] = null;
        $this->rs['state_allowed'] = null;
        $this->rs['state_visible'] = null;
        $this->rs['state_notified'] = null;
        $this->rs['embedded_item_ids'] = null;

        if ($serial) {
            $this->retrieve_record($serial);
        }
        
        $this->serial = $serial;
    }

    // Process incoming data
    public function process($data)
    {
        // Check if data has been passed to model
        if (! $data) {
            throw new Exception("Error Processing Request: No data found", 1);
        } else {

            // Delete old data
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming background_items.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process each of the items
            foreach ($plist as $item_entry) {
                // Add the serial mumber to each entry
                $item_entry['serial_number'] = $this->serial_number;

                // Process each item's keys
                foreach ($this->rs as $key => $value) {
                    // If key exists and is zero, set it to zero
                    if ( array_key_exists($key, $item_entry) && $item_entry[$key] === 0) {
                        $this->rs[$key] = 0;
                    // Else if key does not exist in $item_entry, null it
                    } else if (! array_key_exists($key, $item_entry) || $item_entry[$key] == '' || $item_entry[$key] == "{}" || $item_entry[$key] == "[]") {
                        $this->rs[$key] = null;

                    // Set the db fields to be the same as those in the preference file
                    } else {
                        $this->rs[$key] = $item_entry[$key];
                    }
                }

                // Save the data, save the backpacks with snacks because I'm hungry
                $this->save();
            }
        }
    }
}
