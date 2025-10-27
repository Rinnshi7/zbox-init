<?php include $app->getModuleRoot() . 'transfer/view/showimport.html.php';?>
<script>
$(document).ready(function()
{
    setTimeout(function()
    {
        $('#showData thead tr').append('<th class="w-70px"><?php echo $lang->actions;?></th>');

        $('#showData tbody tr').each(function(index, value)
        {
            $(this).append('<td><a id="'+$(this).attr('id')+'" onclick="delItem(this)"><i class="icon-close"></i></a></td>');
        })
    },
    50);
})

function delItem(val)
{
    $(val).parents('tr').remove();
}
</script>
