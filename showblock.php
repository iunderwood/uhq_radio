<?php

include '../../mainfile.php';

include XOOPS_ROOT_PATH."/header.php";

$xoopsOption['template_main'] = 'showblock.html';

$xoopsTpl->assign('blkid','56');

echo("bleh.");

include XOOPS_ROOT_PATH."/footer.php";

?>