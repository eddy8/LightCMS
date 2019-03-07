<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateModelRelatedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:basic {model : model name} {chineseName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate model related files, such as model controller, repository, request and so on';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = trim($this->argument('model'));
        $modelChineseName = \mb_convert_encoding(trim($this->argument('chineseName')), 'utf-8', ['utf-8', 'gbk']);
        $modelPlural = Str::plural($model);
        $modelUCFirst = Str::ucfirst($model);

        $template = file_get_contents(storage_path() . '/template/template.txt');

        $types = ['repository', 'controller', 'request', 'model', 'view', 'route'];
        foreach ($types as $type) {
            if (preg_match_all("%!start{$type}=(.+?\.php)(.+?)!end{$type}%s", $template, $match)) {
                foreach ($match[1] as $k => $v) {
                    if ($type === 'view') {
                        $path = resource_path('views/admin/' . $model);
                        if (!is_dir($path)) {
                            mkdir($path);
                        }
                        $file = resource_path() . '/' . str_replace('{{-$model-}}', $model, $v);
                    } elseif ($type === 'route') {
                        $file = base_path('routes/auto/' . str_replace('{{-$model-}}', $model, $v));
                    } else {
                        $file = app_path() . '/' . str_replace('{{-$model_uc_first-}}', $modelUCFirst, $v);
                    }
                    if (file_exists($file)) {
                        echo "{$file} 已存在" . PHP_EOL;
                        continue;
                    }

                    $content = ltrim(str_replace('{{-$model_uc_first-}}', $modelUCFirst, $match[2][$k]));
                    $content = str_replace('{{-$model_chinese_name-}}', $modelChineseName, $content);
                    $content = str_replace('{{-$model-}}', $model, $content);
                    $content = str_replace('{{-$model_plural-}}', $modelPlural, $content);
                    file_put_contents($file, $content);
                }
            }
        }

        echo 'All files created successfully';
        return;
    }
}
