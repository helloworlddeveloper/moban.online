
function nv_cat_del( catid )
{
    if ( confirm( cat_del_cofirm ) )
    {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'del=1&catid=' + catid + '&num=' + nv_randomPassword( 8 ), function(res) {
            if( res == 'OK' )
            {
                window.location.href = window.location.href;
            }
            else
            {
                alert( nv_is_del_confirm[2] );
            }
        });
    }
    return false;
}

function nv_chang_cat_weight( catid )
{
    var nv_timer = nv_settimeout_disable( 'weight' + catid, 2000 );
    var newpos = document.getElementById( 'weight' + catid ).options[document.getElementById( 'weight' + catid ).selectedIndex].value;
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'changeweight=1&catid=' + catid + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), function(res) {
        if ( res != 'OK' )
        {
            alert( nv_is_change_act_confirm[2] );
        }
        clearTimeout( nv_timer );
        window.location.href = window.location.href;
        return;
    });
}