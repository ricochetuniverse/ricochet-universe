<?php

namespace Deployer;

desc('Execute artisan view:cache');
task('artisan:view:cache', function () {
    run('{{bin/php}} {{release_path}}/artisan view:cache');
});
