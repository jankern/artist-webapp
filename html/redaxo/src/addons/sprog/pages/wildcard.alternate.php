<?php
use Sprog\Wildcard;

$querySelect = [];
$queryJoin = [];
foreach (\rex_clang::getAll() as $clang) {
    if (rex::getUser()->getComplexPerm('clang')->hasPerm($clang->getId())) {
        $as = 'clang' . $clang->getId();
        $querySelect[] = 'clang' . $clang->getId() . '.replace AS ' . 'clang_id_' . $clang->getId();
        $queryJoin[] = '
            LEFT JOIN   ' . rex::getTable('sprog_wildcard') . ' AS ' . 'clang' . $clang->getId() . ' 
                ON      a.id = ' . 'clang' . $clang->getId() . '.id 
                AND     clang' . $clang->getId() . '.clang_id = ' . $clang->getId();
    }
}
$querySelectAsString = count($querySelect) ? ', ' . implode(',', $querySelect) : '';

$search = '';
$sql = rex_sql::factory();
//$sql->setDebug();
$items = $sql->getArray('
    SELECT 
        DISTINCT    a.id, 
                    a.wildcard AS wildcard
                    ' . $querySelectAsString . ' 
    FROM            ' . rex::getTable('sprog_wildcard') . ' AS a 
        ' . implode(' ', $queryJoin) . ' 
    WHERE           1 ' . $search . ' 
    ORDER BY        wildcard');


if (count($items)) {
    $toc = [];
    $content = '';
    foreach ($items as $item) {

        $toc[] = '<a href="#' . $item['wildcard'] . '">' . $item['wildcard'] . '</a>';

        $docs = '';
        /*
        $docs .= '<div class="cheatsheet-docs-block">';
        $docs .= '<a name="' . $item['wildcard'] . '"></a>';
        $docs .= '<h3 class="cheatsheet-docs-code-heading">' . $item['wildcard'] . '</h3>';

        $docs .= '<table class="cheatsheet-docs-table"><colgroup><col width="180px" /><col width="*" /></colgroup>';
        $docs .= '<thead>';
        $docs .= '<tr><th>Platzhalter</th><td><code>' . Wildcard::getOpenTag() . $item['wildcard'] . Wildcard::getCloseTag() . '</code></td></tr>';
        $docs .= '</thead><tbody>';
        foreach (\rex_clang::getAll() as $clang) {
            $docs .= '<tr><th>' . $clang->getName() . '</th><td>' . $item['clang_id_' . $clang->getId()] . '</td></tr>';
        }
        $docs .= '</tbody></table>';
        $docs .= '</div>';
        */
        if (rex::getUser()->isAdmin() && rex_get('wildcard_id') == $item['id']) {
            $docs .= '<div class="cheatsheet-docs-block ao-action-body">';
        } else {
            $docs .= '<div class="cheatsheet-docs-block">';
        }
        $docs .= '<a name="' . $item['wildcard'] . '"></a>';
        $docs .= '<table class="cheatsheet-docs-table" style="table-layout: fixed"><colgroup><col width="180px" /><col width="*" /><col width="100px" /></colgroup>';
        $docs .= '<thead>';
        $docs .= '<tr>
                <td colspan="2" style="background-color: #f7f7f7; vertical-align: middle;">
                    <code style="white-space: nowrap"><span class="text-muted">' . Wildcard::getOpenTag() . '</span>' . $item['wildcard'] . '<span class="text-muted">' . Wildcard::getCloseTag() . '</span></code>
                </td>';
        $docs .= '
                <td class="text-right" style="background-color: #f7f7f7; font-size: 16px; font-weight: 400;">';

        if (rex::getUser()->isAdmin() && rex_get('wildcard_id') == $item['id']) {
            $docs .= '<button class="ao-btn ao-btn-primary" type="submit">speichern</button>';
        } else {
            $docs .= '  <a data-pjax-container="#rex-js-page-container" class="btn-link" style="color: #ccc; padding: 5px;" href="#"><i class="fa fa-search"></i></a>
                        <a data-pjax-container="#rex-js-page-container" class="btn-link" style="color: #ccc; padding: 5px;" href="' . rex_url::currentBackendPage(['func' => 'edit', 'wildcard_id' =>  $item['id']]) . '"><i class="fa fa-pencil"></i></a>
                        <a data-pjax-container="#rex-js-page-container" class="btn-link" style="color: #ccc; padding: 5px;" href="#"><i class="fa fa-trash"></i></a>';
        }

        $docs .= '
                </td>
            </tr>';
        $docs .= '</thead>';
        $docs .= '<tbody>';
        $docs .= '<tr>';
        if (rex::getUser()->isAdmin() && rex_get('wildcard_id') == $item['id']) {
            $docs .= '<th style="padding-left: 100px;">Kommentar</th><td><input class="form-control" type="text" value="The rowspan attribute defines the number of rows a header cell should span." /></td>';
        } else {
            $docs .= '<td class="text-muted" style="padding-left: 100px;">Kommentar</td><td>The rowspan attribute defines the number of rows a header cell should span.</td>';
        }
        $docs .= '<td></td></tr>';
        foreach (\rex_clang::getAll() as $clang) {
            $docs .= '<tr>';
            if (rex::getUser()->isAdmin() && rex_get('wildcard_id') == $item['id']) {
                $docs .= '<th style="padding-left: 100px;">' . $clang->getName() . '</th><td><textarea class="form-control" >' . htmlspecialchars($item['clang_id_' . $clang->getId()]) . '</textarea></td>';
            } else {
                $docs .= '<th class="text-muted" style="padding-left: 100px;">' . $clang->getName() . '</th><td>' . $item['clang_id_' . $clang->getId()] . '</td>';
            }
            $docs .= '<td></td></tr>';
        }
        $docs .= '</tbody></table>';
        $docs .= '</div>';

        $content .= $docs;
    }

    $content = '<nav class="cheatsheet-docs-toc"><ul class="cheatsheet-docs-toc-list"><li>' . implode('</li><li>', $toc) . '</li></ul></nav>' . $content;

    $fragment = new rex_fragment([
        'title' => 'Platzhalter',
        'body' => $content,
    ]);
    $content = $fragment->parse('core/page/section.php');

    echo $content;
}

?>

<style>
    thead code {
        font-size: 100%;
    }
    textarea.form-control {
        height: 36px;
    }
    .ao-action-body table {
        border-top-color: #28a745;
    }
    .ao-action-body table tr th:first-child,
    .ao-action-body table tr td:first-child {
        border-left: 1px solid #28a745;
        padding-left: 9px;
    }
    .ao-action-body table tr th:last-child,
    .ao-action-body table tr td:last-child {
        border-right: 1px solid #28a745;
        padding-right: 9px;
    }
    .ao-action-body table tbody tr:last-child th,
    .ao-action-body table tbody tr:last-child td {
        border-bottom-color: #28a745;
    }
    .ao-btn {
        position: relative;
        display: inline-block;
        font-weight: 600;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-repeat: repeat-x;
        background-position: -1px -1px;
        background-size: 110% 110%;
        border: 1px solid rgba(27, 31, 35, 0.2);
        border-radius: 0.25em;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;

        color: #24292e;
        background-color: #eff3f6;
        background-image: -webkit-linear-gradient(270deg, #fafbfc 0%, #eff3f6 90%);
        background-image: linear-gradient(-180deg, #fafbfc 0%, #eff3f6 90%);

        padding: 3px 10px;
        font-size: 12px;
        line-height: 20px;
    }

    .ao-btn-primary {
        color: #fff;
        background-color: #28a745;
        background-image: -webkit-linear-gradient(270deg, #34d058 0%, #28a745 90%);
        background-image: linear-gradient(-180deg, #34d058 0%, #28a745 90%)
    }
</style>
<script>
$(document).on('rex:ready', function(event, container) {
    $('textarea.form-control').each(function () {
        $(this).attr('style', 'height:' + (this.scrollHeight) + 'px; overflow-y: hidden;');
    }).on('input', function () {
        this.style.height = '36px';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
