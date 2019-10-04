<?php
require "header.php";
?>

<?php
if (!$resultset) {
    return;
}
?>

<?php
$defaultRoute = "?route=show-all-sort&"
?>
<table>
  <tr class="first">
      <th>Rad</th>
      <th>Id </th>
      <th>Bild </th>
      <th>Titel </th>
      <th>År </th>
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
