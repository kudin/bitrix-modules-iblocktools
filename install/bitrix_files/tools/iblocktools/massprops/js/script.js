var sListTable = '';
var sStartCh = '';
var sEndCh = '';

$(document).ready(function () {
    $('.list input').live('click', function (e) {
        if ($(this).attr('name') == 'ID[]')
        {
            if (sStartCh.length > 0 && e.shiftKey && sStartCh != $(this).val())
                sEndCh = $(this).val();
            else
                sStartCh = $(this).val();

            var bWasChecked = false;
            var bDoCheck = false;

            if (e.shiftKey && sStartCh.length > 0 && sEndCh.length > 0)
            {
                $('.list input').each(function () {

                    if ($(this).attr('name') == 'ID[]')
                    {
                        if ($(this).val() == sStartCh || $(this).val() == sEndCh)
                        {
                            bDoCheck = !bDoCheck;
                            bWasChecked = true;
                        }
                        if (bDoCheck)
                        {
                            this.checked = true;
                            obListTable = new JCAdminList(sListTable);
                            obListTable.SelectRow(this);
                            $(this).attr('checked', true);
                        }
                    }
                });
            }

            if (bWasChecked)
                sStartCh = sEndCh = '';

        }
    });
    var seprops_dest = $("#seprops_dest").html();
    $("#seprops_dest").html('');
    $($('#seprops_dest').parents('form')).append('<div id="seprops_dest2" style="display:none; padding:12px;">' + seprops_dest + '</div>');
    $(".adm-list-footer-ext #seprops_dest").remove();
});


function se_props_selected(propID, iblockID) {
    if (propID != 'null') {
        $.ajax({
            url: '/bitrix/softeffect.props/ajax/ajax.php?propID=' + propID + '&iblockID=' + iblockID + '&ajax=Y',
            success: function (data) {
                $('#seprops_dest_l2').html(data);
            }
        });

    } else {
        document.getElementById('seprops_dest_l2').innerHTML = '';
    }
}

function se_props_selectedHL(propID, iblockID) {
    if (propID != 'null') {
        jsAjaxUtil.InsertDataToNode('/bitrix/tools/iblocktools/massprops/ajax/ajaxHL.php?propID=' + propID + '&iblockID=' + iblockID + '&ajax=Y', 'seprops_dest_l2', true);
    } else {
        document.getElementById('seprops_dest_l2').innerHTML = '';
    }
}
