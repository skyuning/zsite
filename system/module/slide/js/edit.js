$(document).ready(function()
{
    var key = v.key;
    $(document).on('click', 'a.plus', function()
    {
        $(this).parents('tr').after($('#button').html().replace(/key/g, key));
        key ++;
    });

    /* Delete options. */
    $(document).on('click', '.delete', function()
    {
        if($(this).parents('table').find('a.delete').size() == 1)
        {
            $(this).parents('tr').find('input').val('');
        }
        else
        {
            $(this).parents('tr').remove();
        }
    });

    $('.button-target').change();
    $('.global-button').change();

    $('#sourceImage').hide();
    $('#uploadFile').hide();
})

function fileHide()
{
    $('#sourceImage').attr('disabled', false);
    $('#sourceImage').show();
    $('#uploadFile').show();
    $('[name*=files]').hide();
}

function fileShow()
{
    $('[name*=files]').show();
    $('#sourceImage').hide();
    $('#uploadFile').hide();
    $('#sourceImage').attr('disabled', true);
}
