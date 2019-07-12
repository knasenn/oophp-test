<?php

namespace Anax\View;

?><h1>Get 100 game</h1>
<br>
<form method="post">
    <input type="submit" name="roll" value="ROLL">
    <input type="submit" name="save" value="SAVE">
    <input type="submit" name="reset" value="RESTART">
</form>
<h3>You rolled a: <?= $roll1 ?> and a <?= $roll2 ?> and gives you the combined sum: <?= $fullHand ?><br>
Save or roll again?</h3>


<h3>Player total score: <?= $player1 ?></h3>
<table>
    <?php foreach ($playerScores as $value) { ?>
    <tr>
        <td><?php echo $value; ?></td>
    </tr>
    <?php } ?>
</table>
<h3>Computer total score: <?= $player2 ?></h3>
<table>
    <?php foreach ($computerScores as $value) { ?>
    <tr>
        <td><?php echo $value; ?></td>
    </tr>
    <?php } ?>
</table>
