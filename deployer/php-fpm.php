<?php

namespace Deployer;

desc('Reload PHP-FPM service');
task('php-fpm:reload', function () {
    // The user must have rights to restart service
    //
    // sudo visudo -f /etc/sudoers.d/php-fpm
    //
    // username ALL=NOPASSWD:/bin/systemctl reload php7.2-fpm.service
    run('sudo /bin/systemctl reload php7.2-fpm.service');
});
