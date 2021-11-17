/* Live Suche für module_select */
let $modules, $modulesSearch;

$(document).on('rex:ready', function () {

    $modulesSearch = $('.module-search');
    //$modules = $('.rex-slice-select .indy-list li a, .rex-slice-select .indy-col a');

  jQuery.expr[':'].icontains = function (a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
  };

  $modulesSearch.on('keyup', function () {
    const value = $(this).val();

    $modulesSearchId = $(this).attr('data-indy');
    $modulesSearchParent = $('#'+$modulesSearchId);
    console.log($modulesSearchId);
    $modules = $modulesSearchParent.find('.indy-list li a, .indy-col a');

    if (value) {
      $modules.parent().hide();
      $modules.filter(':icontains(' + value + ')').parent().show();
    }
    else {
      $modules.parent().show();
    }
  });

});