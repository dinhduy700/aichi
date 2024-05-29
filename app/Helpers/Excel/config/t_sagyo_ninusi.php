<?php
$config = require(app_path('Helpers/Excel/config/t_sagyo.php'));
$config['block'][2]['B']['mergeCells'] = ['w'=>5, 'h'=>1];
return $config;
