<?php
    $progress = ($totalwords / 1000000) * 100;
    $progress = round($progress, 0, PHP_ROUND_HALF_UP);
?>
<div class="progress-bar bg-info" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"><?= $progress ?>%</div>
