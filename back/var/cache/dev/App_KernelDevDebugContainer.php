<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container3QngAfS\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container3QngAfS/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container3QngAfS.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container3QngAfS\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \Container3QngAfS\App_KernelDevDebugContainer([
    'container.build_hash' => '3QngAfS',
    'container.build_id' => 'f42d63a2',
    'container.build_time' => 1739373040,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'Container3QngAfS');
