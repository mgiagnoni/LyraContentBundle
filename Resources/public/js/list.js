jQuery().ready(function() {
    $('#ly-list-wrapper')
       .addClass('ui-widget ui-widget-content ui-corner-all');
    
    $('#ly-list-wrapper h1')
        .addClass('ui-widget-header ui-corner-all');
        
    $('table.ly-list')
        .addClass('ui-widget');
        
    $('table.ly-list td')
        .addClass('ui-widget-content');

    // Sortable headers
    $('table.ly-list th.sorted-asc')
        .append("<span class='ui-icon ui-icon-triangle-1-s'></span>")

    $('table.ly-list th.sorted-desc')
        .append("<span class='ui-icon ui-icon-triangle-1-n'></span>")

    $('table.ly-list th')
        .addClass('ui-widget-content ui-state-default');

    // Link buttons
    $('a.button')
        .each(function() {
            // Extracts icon name from class attribute
            var action = /action-(\S+)/.exec(this.className);
            $(this).button({
                text: $(this).hasClass('icon-only') ? false : true,
                icons: {
                  primary: action !== null ? configUI.icons[action[1]] : null
                }
            });
        });
        
    // Order actions buttons
    $('input.button')
        .each(function() {
            var action = /action\[([^\]]+)/.exec($(this).attr('name'));
            $('<button></button>')
                .text($(this).attr('value'))
                .button({
                    disabled: $(this).hasClass('disabled') ? true : false,
                    text: $(this).hasClass('icon-only') ? false : true,
                    icons: {
                        primary: configUI.icons[action[1]]
                    }
                })
                .click(function(e) {
                    e.preventDefault();
                    $(this).next().click();
                })
                .insertBefore($(this).hide())
        });
        
    // Modal dialog to confirm delete operations

    var showDialog = function() {
        $(".buttons", this).hide()
        var buttonOk = $("input[name='submit']", this);
        var buttonsOpts = {};
        buttonsOpts[buttonOk.hide().val()] = function() {
            buttonOk.click();
            $(this).dialog("close");
        };
        buttonsOpts[buttonOk.next().hide().text()] = function() {
            $(this).dialog("close");
        };
        $(this).dialog({
            modal: true,
            autoOpen: true,
            resizable: false,
            minHeight: 90,
            width: 400,
            title: $("h1", this).hide().text(),
            close: function() {$(this).remove()},
            buttons: buttonsOpts
        })
    }
  
    $(".action-delete, .action-move")
        .click(function(e) {
            e.preventDefault();

            $("<div></div>")
                .appendTo("body")
                .load(this.href, showDialog);
        })
});
