<?php

$account = $this->Session->read("Account");

if($account['id']):
?>

<h2 id="page_title">Account Index</h2>

<p><a href="<?= $this->base ?>/players/add"><i class="fa fa-plus"></i> New Player</a></p>

<p><a href="<?= $this->base ?>/games/add"><i class="fa fa-plus"></i> New Game</a></p>

<?
endif;
?>