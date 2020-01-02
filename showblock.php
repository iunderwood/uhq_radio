<?php

include __DIR__ . '/../../mainfile.php';

include XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsOption']['template_main'] = 'showblock.tpl';

$xoopsTpl->assign('blkid', '56');

echo('bleh.');

include XOOPS_ROOT_PATH . '/footer.php';
