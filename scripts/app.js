$(function () {
    $('.remove-item[data-toggle="popover"]').popover({
        content: 'This row is associated with an inventory item.',
        placement: 'right'
    });
});

//Hide the popover if the popover box has been clicked
$(document).on('click', '.popover', function() {
    (($('[data-toggle="popover"]').popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
});

//Hide the popover if outside the popover box has been clicked
$(document).on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
        }
    });
});

//Add a row to an admin page
function addAdminRow(parentClass, elementName) {
    var html = $('.' + parentClass).last().html();
    var rowId = parseInt($(html).find('.remove-item').first().attr('data-row'), 10) + 1;

    var item = document.createElement('div');
    item.setAttribute('class', 'row ' + parentClass);
    item.innerHTML = html;

    //If there are any values in any input or textarea, reset them.
    $(item).find('input, select, span').each(function (key, element) {
        $(element).val('');

        if ($(element).hasClass('id')) {
            $(element).remove();
        }

        if ($(element).hasClass('code')) {
            $(element).attr('name', elementName + '[' + rowId + '][code]');
        } else if ($(element).hasClass('name')) {
            $(element).attr('name', elementName + '[' + rowId + '][name]')
        } else if ($(element).hasClass('disabled')) {
            $(element).html('NEW');
        } else if ($(element).hasClass('remove-item') && !$(element).hasClass('btn-danger')) {
            $(element).removeClass('btn-secondary').addClass('btn-danger');
        }
    });

    $(item).find('.remove-item').first().attr('data-row', rowId);

    return item;
}

//Remove a row from an admin page
function removeAdminRow(element, parentClass) {
    var parent = $(element).parents('.' + parentClass);
    var id = $(parent).find('.id').first().val();
    console.log(parent, id);

    //Make sure the ID is defined. Newly added rows won't have an ID
    if (typeof id !== 'undefined') {
        var deleteIds = [];
        if (document.getElementById('delete').value.length) {
            deleteIds = document.getElementById('delete').value.split(',');
        }

        deleteIds.push(id);
        document.getElementById('delete').value = deleteIds.join(',');
    }

    $(parent).remove();

    //Hide the remove button if there is only one item in the list
    if (!($('.' + parentClass).length > 1)) {
        $('.remove-item').addClass('d-none');
    }
}
