<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerXxH7FwY\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerXxH7FwY/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerXxH7FwY.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerXxH7FwY\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerXxH7FwY\App_KernelDevDebugContainer([
    'container.build_hash' => 'XxH7FwY',
    'container.build_id' => 'da525281',
    'container.build_time' => 1710474682,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerXxH7FwY');
