<?php
// ************************
//Kanske lägga till namespace ovan
// ************************
require "header.php";
?>

<form method="get">
    <fieldset>
    <legend>Search</legend>
    <input type="hidden" name="route" value="search-year">
    <p>
        <label>Created between:
        <input type="number" name="year1" value="<?= $year1 ?: 1900 ?>" min="1900" max="2100"/>
        -
        <input type="number" name="year2" value="<?= $year2  ?: 2100 ?>" min="1900" max="2100"/>
        </label>
    </p>
    <p>
        <input type="submit" name="doSearch" value="Search">
    </p>
    <p><a href="?">Show all</a></p>
    </fieldset>
</form>

<?php
$defaultRoute = "?route=show-all-sort&"
?>
<table>
  <tr class="first">
      <th>Rad</th>
      <th>Id <?= orderby("id", "$defaultRoute") ?></th>
      <th>Bild <?= orderby("image", $defaultRoute) ?></th>
      <th>Titel <?= orderby("title", $defaultRoute) ?></th>
      <th>År <?= orderby("year", $defaultRoute) ?></th>
  </tr>
<?php $id = -1; foreach ($resultset as $row) :
    $id++; ?>
    <tr>
        <td><?= $id ?></td>
        <td><?= $row->id ?></td>
        <td><img class="thumb" src="<?= $row->image ?>"></td>
        <td><?= $row->title ?></td>
        <td><?= $row->year ?></td>
    </tr>
<?php endforeach; ?>
</table>

<?php
require "footer.php";
?>
