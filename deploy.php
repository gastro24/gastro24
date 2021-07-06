<?php
namespace Deployer;

require 'recipe/zend_framework.php';


// Project name
set('application', 'Gastro24');

// Project repository
set('repository', 'git@gitlab.cross-solution.de:YAWIK/Gastro24.git');

// Shared files/dirs between deploys
add('shared_files', [
    'test/sandbox/public/.htaccess',
    'test/sandbox/public/pro-sitemaps-4112318.php'
]);
add('shared_dirs', [
    'test/sandbox/var/log',
    'test/sandbox/var/cache',
    'test/sandbox/config/autoload',
    'test/sandbox/public/static',
    'test/sandbox/public/lg'
]);

// Writable dirs by web server
add('writable_dirs', [
    'test/sandbox/var/cache',
    'test/sandbox/var/log',
    'test/sandbox/public/static',
    'test/sandbox/public/lg'
]);

set('default_stage', 'prod');

// Hosts
host('php7.gastrojob24.ch')
    ->user('yawik')
    ->stage('prod')
    ->multiplexing(false)
    ->set('deploy_path', '/var/www/production');

host('staging.gastrojob24.ch')
    ->user('yawik')
    ->stage('staging')
    ->multiplexing(false)
    ->set('deploy_path', '/var/www/production');


after('deploy:symlink', 'restart');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

task('restart', '  
    sudo /bin/systemctl restart php7.4-fpm.service;
');
    