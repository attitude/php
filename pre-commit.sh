#!/usr/bin/php
<?php echo shell_exec("./vendor/attitude/index-php/run.sh -path src -backup false && git add **/\*.php");