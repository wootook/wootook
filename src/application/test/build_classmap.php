<?php

function instance($directory)
{
    return new \FilesystemIterator($directory, \FilesystemIterator::KEY_AS_PATHNAME | /*\FilesystemIterator::CURRENT_AS_SELF |*/
        \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
}

function export(Array &$fileList, \FilesystemIterator $iterator, $baseNamespace = null, $basePath = null) {
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            export($fileList, instance($file->getPathname()),
                ($baseNamespace === null ? $file->getBasename('.php') : $baseNamespace . "\\" . $file->getBasename('.php')),
                ($basePath === null      ? $file->getBasename()       : $basePath . "/" . $file->getBasename()));
        } else if (strtolower($file->getExtension()) !== 'php') {
            continue;
        } else {
            $className = $baseNamespace . '\\' . $file->getBasename('.php');
            $fileList[$className] = $basePath . '/' . $file->getBasename();
        }
    }
};

$directory = dirname(__DIR__) . '/code/core/WootookCore/src';
$fileList = array();

export($fileList, instance($directory));

$longest = 0;
foreach ($fileList as $class => $file) {
    $length = strlen($class);
    if ($length > $longest) {
        $longest = $length;
    }
}
echo "<?php return array(\n";
foreach ($fileList as $class => $file) {
    $pad = str_pad('', $longest - strlen($class), ' ', STR_PAD_RIGHT);
    echo "    '{$class}'{$pad} => __DIR__ . '/src/{$file}',\n";
}
echo "    );\n";

