jQuery().ready(function() {
    $('#ly-form-wrapper')
        .addClass('ui-widget ui-widget-content ui-corner-all');
    
    $('#ly-form-wrapper h1')
        .addClass('ui-widget-header ui-corner-all');
    
    $('.ly-form fieldset')
        .addClass('ui-widget-content');
    
    $('.button')
        .each(function() {
            // Extracts icon name from class attribute
            var action = /action-(\S+)/.exec(this.className);
            $(this).button({
                icons: {
                  primary: action !== null ? configUI.icons[action[1]] : null
                }
            });
        });
        
    $(".ly-form legend").each(function() {
      $(this).parent().before(
      $("<h3 class='ui-widget-header ui-corner-top'></h3>")
        .click(function() {
          var fs = $(this).next();
          var sfx = ['n','s'];
          fs.toggle();
          $(this).find("span")
            .addClass('ui-icon-triangle-1-' + (fs.css('display') == 'none' ? sfx.pop() : sfx.shift()))
            .removeClass('ui-icon-triangle-1-' + sfx[0]);
        })
        .disableSelection()
        .text($(this).hide().text())
        .append("<span class='ui-icon ui-icon-triangle-1-n'></span>")
      )
    });
    
    $('ul.error-list li').addClass('ui-state-error');
});
