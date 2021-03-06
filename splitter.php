<?php
/**
 * Split each core plugin and theme into separate repos and push the to GitHub. For
 * example, the "CMS" plugin is mapped to "git@github.com:quickapps-plugins/cms.git"
 *
 * ### Usage:
 *
 * ```
 * php splitter.php --main-branch="2.0" --plugins="Block,Bootstrap"
 * ```
 *
 * - If no main branch name is provided "2.0" will be used by default.
 * - If no plugin names are given, all of them will be splitted.
 */

/**
 * Main branch name.
 *
 * @var string
 */
$options = getopt('', ['main-branch::', 'plugins::']);
if (empty($options['main-branch'])) {
    echo "No main branch name given, using '2.0' by default.\n";
    $mainBranch = '2.0';
} else {
    $mainBranch = $options['main-branch'];
}

/**
 * List of core plugins located in the "plugins" directory.
 *
 * @var array
 */
$plugins = [
    'Block',
    'Bootstrap',
    'Captcha',
    'CMS',
    'Comment',
    'Content',
    'Eav',
    'Field',
    'Installer',
    'Jquery',
    'Locale',
    'MediaManager',
    'Menu',
    'Search',
    'System',
    'Taxonomy',
    'User',
    'Wysiwyg',
    'BackendTheme',
    'FrontendTheme',
];

if (!empty($options['plugins'])) {
    $plugins = array_intersect($plugins, explode(',', $options['plugins']));
}

/**
 * Null device, based on OS.
 *
 * @var string
 */
$null = DIRECTORY_SEPARATOR === '/' ? '/dev/null' : 'NUL';

/**
 * Creates a new branch for every plugin and theme, and push to corresponding GitHub
 * repository. Such branches are removed after pushed.
 */
foreach ($plugins as $plugin) {
    $org = 'quickapps-plugins';
    if (strpos($plugin, 'Theme') !== false) {
        $plg = strtolower(str_replace('Theme', '-theme', $plugin));
        $org = 'quickapps-themes';
    } else {
        $plg = strtolower($plugin);
    }

    echo "Processing: {$plugin}\n";
    echo str_repeat('-', strlen("Processing: {$plugin}")) . "\n\n";

    exec("git checkout {$mainBranch} > {$null}");
    exec("git remote add {$plg} git@github.com:{$org}/{$plg}.git -f 2> {$null}");
    exec("git branch -D {$plg} 2> {$null}");
    exec("git checkout -b {$plg}");
    exec("git filter-branch --prune-empty --subdirectory-filter plugins/{$plugin} -f {$plg}");
    exec("git push {$plg} {$plg}:master --force");
    exec("git checkout {$mainBranch} > {$null}");
    exec("git branch -D {$plg}");
    exec("git remote rm {$plg}");

    echo "\n\n";
}
