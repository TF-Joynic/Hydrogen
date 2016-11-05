<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2016/10/23 0023
 * Time: 23:42
 */
$line = '<h1>Hello World! <?php echo $this->name; ?>&nbsp;{$name}{$age}</h1>';
echo preg_replace_callback('/{\$([^}]+)}/i', function ($matches) {
    if ($matches && 2 == count($matches)) {
        return '<?php echo $'.$matches[1].';?>';
    }

    return '';
}, $line);