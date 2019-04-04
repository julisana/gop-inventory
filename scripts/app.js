$(function () {
    $('.remove-item[data-toggle="popover"]').popover({
        content: 'This row is associated with an inventory item.',
        placement: 'right'
    });
});

//Hide the popover if the popover box has been clicked
$(document).on('click', '.popover', function () {
    (($('[data-toggle="popover"]').popover('hide').data('bs.popover') || {}).inState || {}).click = false  // fix for BS 3.3.6
});

//Hide the popover if outside the popover box has been clicked
$(document).on('click', function (e) {
    $('[data-toggle="popover"],[data-original-title]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            (($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false  // fix for BS 3.3.6
        }
    });
});

//Add a row to an admin page
function addRow(rowClass) {
    var html = $('.' + rowClass).last().html();
    var rowId = parseInt($(html).find('.remove-item').first().attr('data-row'), 10) + 1;

    var item = document.createElement('div');
    item.setAttribute('class', 'row pt-3 ' + rowClass);
    item.innerHTML = html;

    //If there are any values in any input or textarea, reset them.
    $(item).find('input, select, span').each(function (key, element) {
        $(element).val('');

        //If the element supplies a default value, set it on the new line
        if (typeof $(element).attr('data-default') !== 'undefined') {
            $(element).val($(element).attr('data-default'));
        }

        if ($(element).hasClass('id')) {
            $(element).remove();
        }

        //Change the ID display
        if ($(element).hasClass('disabled')) {
            $(element).html('NEW');
        }
        //Make the remove button usable again
        else if ($(element).hasClass('remove-item') && $(element).hasClass('btn-secondary')) {
            $(element).removeClass('btn-secondary').addClass('btn-danger');
        }
    });

    return item;
}

//Remove a row from an admin page
function removeRow(element, rowClass) {
    var parent = $(element).parents('.' + rowClass);
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
    if (!($('.' + rowClass).length > 1)) {
        $('.remove-item').addClass('d-none');
    }
}

function renameRows(parentClass) {
    //Iterate through children and renumber them
    $('.' + parentClass + ' > div').each(function (key, element) {
        //Set the correct id on the remove button
        $(element).find('.remove-item').first().attr('data-row', key);

        //Iterate through each of the elements and rename them
        $(element).find('input, select').each(function (inputKey, input) {
            //Change the input elements
            if ($(input).is('[name]')) {
                var name = $(input).attr('name').replace(/\[[\d]+\]/ig, '[' + key + ']');
                $(input).attr('name', name);
            }
        });
    });
}
