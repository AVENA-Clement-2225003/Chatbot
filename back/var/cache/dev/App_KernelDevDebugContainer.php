<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerCe1geJO\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerCe1geJO/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerCe1geJO.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerCe1geJO\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerCe1geJO\App_KernelDevDebugContainer([
    'container.build_hash' => 'Ce1geJO',
    'container.build_id' => 'a89c4236',
    'container.build_time' => 1738592076,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerCe1geJO');
