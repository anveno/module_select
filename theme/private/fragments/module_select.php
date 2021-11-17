<?php
/**
 * Discussion Issue #1174
 * Manipulate this fragment to influence the selection of modules on the slice.
 * By default the core fragment is used.
 *
 * @var bool   $block
 * @var string $button_label
 * @var array  $items        array contains all modules
 *             [0]        the index of array
 *             - [id]     the module id
 *             - [title]  the module name
 *             - [href]   the module url
 */

// Eindeutige ID für die Suchfunktion (module-search)
if (rex::getProperty('moduleCounter')) {
    $moduleCounter = rex::getProperty('moduleCounter');
}
else {
    $moduleCounter = 1;
}

// items mit weiteren Infos anreichern:
$extendedModulArray = array();
foreach ($this->items as $item) {

    // defaults
    $catprio    = 999;
    $cat        = 'Unbekannt';
    $prio       = 999;
    $title      = $item['title'];

    // [catprio][cat][prio] title | z.B. [03][Medien][15] Bildergalerie
    if (preg_match('/\[(.*?)\]\[(.*?)\]\[(.*?)\](.*)/', $item['title'], $match) == 1) {
        $catprio    = $match[1];
        $cat        = $match[2];
        $prio       = $match[3];
        $title      = $match[4];
    }

    // Bild anhand von Modul-ID suchen_
    $modul_image_path = 'theme/public/assets/module_select/'; // todo: alternativ auch "project" durchsuchen
    if (file_exists(rex_url::base($modul_image_path.$item['id'].'.png'))) {
        // Medientyp "module_select" inkl. Effekt "Datei: Pfad anpassen"
        $img = '<img src="'.rex_url::media('module_select/' . $modul_image_path.$item['id'].'.png').'">';
    }
    elseif (file_exists(rex_url::base($modul_image_path.$item['id'].'.svg'))) {
        $img = '<img src="'.rex_url::base($modul_image_path.$item['id'].'.svg').'">';
    }
    else {
        $img = '<img src="'.rex_url::base($modul_image_path.'module_image_missing.svg').'">';
    }

    // Werte zuweisen
    $extendedModulArray[$item['id']]['catprio']     = $catprio;
    $extendedModulArray[$item['id']]['cat']         = $cat;
    $extendedModulArray[$item['id']]['prio']        = $prio;
    $extendedModulArray[$item['id']]['title']       = $title;
    $extendedModulArray[$item['id']]['img']         = $img;
    $extendedModulArray[$item['id']]['item']        = $item;
}

// Sortierungsmagie
usort($extendedModulArray, function($a, $b) {
    $retval = $a['catprio'] <=> $b['catprio'];
    if ($retval == 0) {
        $retval = $a['cat'] <=> $b['cat'];
        if ($retval == 0) {
            $retval = $a['prio'] <=> $b['prio'];
            if ($retval == 0) {
                $retval = $a['item']['title'] <=> $b['item']['title'];
            }
        }
    }
    return $retval;
});

//dump($extendedModulArray);

?>

<div class="dropdown btn-block indy-dropdown">

    <button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown"<?= ((isset($this->disabled) && $this->disabled) ? ' disabled' : '') ?>>
        <?php if (isset($this->button_label) && '' != $this->button_label): ?>
        <?= ' <b>' . $this->button_label . '</b>' ?>
        <?php endif; ?>
        <span class="caret"></span>
    </button>

    <div class="dropdown-menu btn-block" role="menu">
        <div class="container-fluid indy-scrollable" id="indy-<?= $moduleCounter; ?>">
            <div class="row text-center">
                <h4>Bitte wählen Sie den gewünschten Inhalts-Typ:</h4>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label class="control-label" for="module-search-<?= $moduleCounter; ?>">
                            <input class="form-control module-search" name="module-search" type="text" data-indy="indy-<?= $moduleCounter; ?>" id="module-search-<?= $moduleCounter; ?>" value="" placeholder="Modul suchen..." />
                        </label>
                   </div>
                    <ul class="indy-list">
                        <?php
                        $catname = '';
                        foreach ($extendedModulArray as $item) {
                            if ($catname != $item['cat']) {
                                echo '<li><strong>'.$item['cat'].'</strong></li>';
                                $catname = $item['cat'];
                            }
                            echo '<li' . ((isset($item['item']['active']) && $item['item']['active']) ? ' class="active"' : '') . (isset($item['item']['attributes']) ? ' ' . trim($item['item']['attributes']) : '') . '>';
                            echo '<a href="' . $item['item']['href'] . '">' . $item['title'] . '</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-8 col-lg-9 remove-padding">
                    <div class="container-fluid">
                        <div class="row row-eq-height">
                        <?php
                        $catname = '';
                        foreach ($extendedModulArray as $item) {
                            if ($catname != $item['cat']) {
                                echo '<div class="col-md-12 col-lg-12 indy-col"><strong>'.$item['cat'].'</strong></div>';
                                $catname = $item['cat'];
                            }
                            echo '<div class="col-md-6 col-lg-4 indy-col' . ((isset($item['item']['active']) && $item['item']['active']) ? ' active' : '') . '"' . (isset($item['item']['attributes']) ? ' ' . trim($item['item']['attributes']) : '') . '>';
                                echo '<a href="' . $item['item']['href'] . '" class="text-center">';
                                    echo '<p class="indy-modul-txt">'.$item['title'].'</p>';
                                    echo '<div class="indy-modul-img">';
                                    echo $item['img'];
                                    echo '</div>';
                                echo '</a>';
                            echo '</div>';
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
    $moduleCounter++;
    rex::setProperty('moduleCounter', $moduleCounter);
?>