<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'deployer/yarn.php';

// Project name
set('application', 'ricochet-levels');

// Project repository
set('repository', 'git@gitlab.com:ngyikp/ricochet-levels.git');
//set('branch', 'deploy');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

inventory('deployer/hosts.yml');

// Tasks

desc('Compile JS/CSS assets');
task('webpack:run', function () {
    $output = run('cd {{release_path}} && yarn run production');
    writeln('<info>' . $output . '</info>');
});

desc('Restart PHP-FPM service');
task('php-fpm:reload', function () {
    // /etc/sudoers:
    //
    //     username ALL=NOPASSWD:/bin/systemctl reload php7.2-fpm.service
    run('sudo /bin/systemctl reload php7.2-fpm.service');
});

after('deploy:vendors', 'yarn:install');
after('deploy:writable', 'webpack:run');
after('artisan:config:cache', 'artisan:route:cache');
before('deploy:symlink', 'artisan:migrate');
before('deploy:symlink', 'deploy:public_disk');

after('deploy', 'artisan:queue:restart');
after('deploy', 'php-fpm:reload');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
