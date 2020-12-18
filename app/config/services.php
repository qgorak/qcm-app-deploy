<?php
use Ubiquity\controllers\Router;
use Ubiquity\security\acl\AclManager;
use Ubiquity\security\acl\persistence\AclCacheProvider;

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();
Router::start();
Ubiquity\cache\CacheManager::startProd($config);
Ubiquity\translation\TranslatorManager::start();
AclManager::start();
AclManager::initFromProviders([
    new AclCacheProvider()
]);
\Ubiquity\security\data\EncryptionManager::start($config);
