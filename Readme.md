Background Items module
==============

Provides background items information printed by `sfltool dumpbtm`, only available on macOS 13 and higher


Table Schema
---

* user (string) Username the item belongs to 
* btm_index (string) UID and index of the item as seen in the raw `sfltool dumpbtm`
* name (string) Name of the background item
* developer_name (string) Developer name
* url (text) File path url of the item
* team_id (string) Team ID of the item
* assoc_bundle_id (text) Associated bundle IDs of of the item
* type (string) Type of the background item
* identifier (text) Identifier of background item
* generation (integer) Generation of item
* parent_id (text) Parent ID of item
* executable_path (text) Path of item's executable path
* state_enabled (integer) If item is enabled
* state_allowed (integer) If item is allowed
* state_visible (integer) If item is visible
* state_notified (integer) If user has been notified of item
* embedded_item_ids (text) Embedded item IDs