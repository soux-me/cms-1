<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
use Field\Utility\DateToolbox;

$settings = $field->metadata->settings;
$phpFormat = DateToolbox::getPHPFormat($field);
$format = empty($settings['format']) ? 'yy-mm-dd' : $settings['format'];
$date = DateToolbox::createFromFormat($format, $field->extra);
?>

<?php if ($field->viewModeSettings['label_visibility'] == 'above'): ?>
    <h3 class="field-label"><?php echo $field->label; ?></h3>
    <p><?php echo date($phpFormat, $date->getTimestamp()); ?></p>
<?php elseif ($field->viewModeSettings['label_visibility'] == 'inline'): ?>
    <p><strong class="field-label"><?php echo $field->label; ?>:</strong> <?php echo date($phpFormat, $date->getTimestamp()); ?></p>
<?php else: ?>
    <p><?php echo date($format, $field->extra); ?></p>
<?php endif; ?>
