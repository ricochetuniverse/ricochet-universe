<?php

namespace Deployer;

require 'contrib/sentry.php';
require 'contrib/npm.php';
require 'recipe/laravel.php';

if (file_exists('deployer/sentry.config.php')) {
    require 'deployer/sentry.config.php';
}

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

set('sentry', [
    'organization' => get('sentry_organization'),
    'projects' => [get('sentry_project')],
    'token' => get('sentry_token'),
]);

// Hosts

import('deployer/hosts.yml');

// Tasks

desc('Compile JS/CSS assets');
task('webpack:run', function () {
    $output = run('cd {{release_path}} && npm run production');
    writeln('<info>'.$output.'</info>');
});

desc('Clear cache for /gateway/catalog.php');
task('ricochet:clear-catalog-cache', function () {
    run('{{bin/php}} {{release_path}}/artisan ricochet:clear-catalog-cache');
});

after('deploy:vendors', 'npm:install');
after('npm:install', 'webpack:run');

after('deploy', 'ricochet:clear-catalog-cache');
after('deploy', 'artisan:queue:restart');

if (get('sentry_token')) {
    after('deploy', 'deploy:sentry');
}

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
