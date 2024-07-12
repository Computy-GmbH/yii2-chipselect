/**
 * Initialise the given element as a chip select container.
 * @param elem
 * @param options additional options.
 */
function initChipSelect(elem, options) {
    const jsOnChange = options.onChange || null;
    elem.find('.chip-selected').on('click', function (e) {
        const container = $(this).closest('.cpty-chip-select-container').find('.unselected-container');
        const value = $(this).data('value');
        const elem = container.find('.chip-unselected[data-value="' + value + '"]');
        $(this).find('input').attr('disabled', true);
        elem.show();
        $(this).hide();
        jsOnChange()
    });

    elem.find('.chip-unselected').on('click', function (e) {
        const container = $(this).closest('.cpty-chip-select-container').find('.selected-container');
        const value = $(this).data('value');
        const elem = container.find('.chip-selected[data-value="' + value + '"]');
        elem.find('input').removeAttr('disabled');
        elem.show();
        $(this).hide();
        jsOnChange()
    });
}
