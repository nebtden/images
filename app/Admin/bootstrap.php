<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Grid\Exporter;
use App\Admin\Extensions\CustomExporter;
use App\Admin\Extensions\DataExporter;


Encore\Admin\Form::forget(['map', 'editor']);
Encore\Admin\Form::forget(['password_again', 'old_password']);
Admin::css('/css/nilo.css');

Exporter::extend('custom-exporter', CustomExporter::class);
Exporter::extend('data-exporter', DataExporter::class);
