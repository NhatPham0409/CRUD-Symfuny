<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerZr742Wo\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerZr742Wo/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerZr742Wo.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerZr742Wo\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerZr742Wo\App_KernelDevDebugContainer([
    'container.build_hash' => 'Zr742Wo',
    'container.build_id' => '35411044',
    'container.build_time' => 1710337648,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerZr742Wo');
