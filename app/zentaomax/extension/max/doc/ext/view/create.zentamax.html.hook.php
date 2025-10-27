<?php if(isset($from) and $from == 'template'):?>
<?php
$templateHTML  = html::select('template', '', '', "class='form-control chosen'");
$confirmHTML   = html::commonButton($lang->confirm, "onclick=loadContent()", "btn btn-primary btn-wide confirm-btn");
$cancelHTML    = html::commonButton($lang->cancel, "data-dismiss='modal'", "btn btn-wide");
$modalTemplate = <<<EOD
<div class='modal fade' id='modalTemplate' data-scroll-inside='true'>
  <div class='modal-dialog' style='width:480px;'>
    <div class='modal-content'>
      <div class='modal-body'>
        <button type='button' class='close' data-dismiss='modal'>
          <i class='icon icon-close'></i>
        </button>
        <table class='table table-form'>
          <tr>
            <th class='temp-tip'>{$lang->doc->selectTemplate}</th>
            <td class='templateBox'>{$templateHTML}</td>
          </tr>
          <tr>
            <td colspan='2' class='text-center'>
              {$confirmHTML}
              {$cancelHTML}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
EOD;
?>
<style>
#modalTemplate .modal-body {padding:50px 50px 30px 10px;}
#modalTemplate .modal-body .close {position:absolute; right:15px; top:15px}
#modalTemplate .confirm-btn {margin-left: 52px;}
#modalTemplate .temp-tip {width: 110px; white-space: nowrap;}
</style>

<script>
$(function()
{
    $('#modalBasicInfo').before(<?php echo json_encode($modalTemplate)?>);
    loadTemplates();
    $('#modalTemplate').modal();
});

/**
 * Load templates.
 *
 * @access public
 * @return void
 */
function loadTemplates()
{
    var link = createLink('baseline', 'ajaxGetTemplates', 'type=all&from=doc&contentType=html,markdown');
    $.post(link, function(data)
    {
        $('#modalTemplate .templateBox').html(data);
        $('#template').removeAttr('onchange').chosen();
    })

    $('#contentType').val('html');
    editor['content'].html('');
    $('#contentBox').removeClass('hidden');
    $('.contenthtml').removeClass('hidden');
    $('.contentmarkdown').addClass('hidden');
}

/**
 * Load content.
 *
 * @access public
 * @return void
 */
function loadContent()
{
    $('#modalTemplate').modal('hide');

    var templateID = $('#template').val();
    if(templateID == '') return;

    var link = createLink('baseline', 'ajaxGetContent', 'templateID=' + templateID);
    $.post(link, function(data)
    {
        data = JSON.parse(data);
        var type = data.type;
        if(type == 'html') type = 'text';
        $('input[id*=' + type + ']').attr('checked', 'checked');
        toggleEditor(data.type);

        if(data.type == 'html')
        {
            var cmd = editor['content'].edit.cmd;
            editor['content'].html('');
            cmd.inserthtml(data.content);
        }
        else if(data.type == 'markdown')
        {
            markdownEditor['contentMarkdown'].value(data.content);
        }
    })
}

/**
 * Toggle editor.
 *
 * @param  string $type
 * @access public
 * @return boolean
 */
function toggleEditor(type)
{
    $('#contentType').val(type);
    if(type == 'html')
    {
        $('.contenthtml').removeClass('hidden');
        $('.contentmarkdown').addClass('hidden');
    }
    else if(type == 'markdown')
    {
        $('.contentmarkdown').removeClass('hidden');
        $('.contenthtml').addClass('hidden');
    }
    return false;
}
</script>
<?php endif;?>
