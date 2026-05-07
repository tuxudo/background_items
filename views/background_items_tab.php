<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/background_items/background_items" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<h2 data-i18n="background_items.background_items"></h2>
<div id="background_items-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="background_items-table-view" class="row pt-4 hide" style="padding-left: 15px; padding-right: 15px;">
  <table class="table table-striped table-condensed table-bordered" id="background_items-table">
    <thead>
      <tr>
        <th data-i18n="background_items.btm_index" data-colname='background_items.btm_index'></th>
        <th data-i18n="background_items.user" data-colname='background_items.user'></th>
        <th data-i18n="background_items.name" data-colname='background_items.name'></th>                
        <th data-i18n="background_items.developer_name" data-colname='background_items.developer_name'></th>
        <th data-i18n="background_items.team_id" data-colname='background_items.team_id'></th>
        <th data-i18n="background_items.assoc_bundle_id" data-colname='background_items.assoc_bundle_id'></th>
        <th data-i18n="background_items.embedded_item_ids" data-colname='background_items.embedded_item_ids'></th>
        <th data-i18n="background_items.state_enabled" data-colname='background_items.state_enabled'></th>
        <th data-i18n="background_items.state_allowed" data-colname='background_items.state_allowed'></th>
        <th data-i18n="background_items.state_visible" data-colname='background_items.state_visible'></th>
        <th data-i18n="background_items.state_notified" data-colname='background_items.state_notified'></th>
        <th data-i18n="background_items.url" data-colname='background_items.url'></th>
        <th data-i18n="background_items.type" data-colname='background_items.type'></th>
        <th data-i18n="background_items.identifier" data-colname='background_items.identifier'></th>
        <th data-i18n="background_items.parent_id" data-colname='background_items.parent_id'></th>
        <th data-i18n="background_items.executable_path" data-colname='background_items.executable_path'></th>
      </tr>
    </thead>
    <tbody>
        <tr>
            <td data-i18n="listing.loading" colspan="16" class="dataTables_empty"></td>
        </tr>
    </tbody>
  </table>
</div>


<script>
$(document).on('appReady', function(){
    // Set blank tab badge
    $('#background_items-cnt').text('');

    $.getJSON(appUrl + '/module/background_items/get_tab_data/' + serialNumber, function(data){
        const $msg = $('#background_items-msg');
        const $tableView = $('#background_items-table-view');
        const $badge = $('#background_items-cnt');

        if (!data?.length) {
            $msg.text(i18n.t('no_data'));
            return;
        }

        // Hide message and show table
        $msg.text('');
        $tableView.removeClass('hide');
        $badge.text(data.length);

        // Helper function to format state columns
        const formatState = (value) => {
            return value == '0' ? '<span class="label label-danger">' + i18n.t('No') + '</span>' :
                   value == '1' ? '<span class="label label-success">' + i18n.t('Yes') + '</span>' : value;
        };

        $('#background_items-table').DataTable({
            data: data,
            autoWidth: false,
            columns: [
                { data: 'btm_index' },
                { data: 'user' },
                { data: 'name' },
                { data: 'developer_name' },
                { data: 'team_id' },
                { data: 'assoc_bundle_id' },
                { data: 'embedded_item_ids' },
                { data: 'state_enabled' },
                { data: 'state_allowed' },
                { data: 'state_visible' },
                { data: 'state_notified' },
                { data: 'url' },
                { data: 'type' },
                { data: 'identifier' },
                { data: 'parent_id' },
                { data: 'executable_path' }
            ],
            createdRow: function(nRow, aData, iDataIndex) {
                // Format all state columns more efficiently
                const stateColumns = [7, 8, 9, 10];
                stateColumns.forEach(colIndex => {
                    const $cell = $('td:eq(' + colIndex + ')', nRow);
                    $cell.html(formatState($cell.text()));
                });
            }
        });
    });
});
</script>