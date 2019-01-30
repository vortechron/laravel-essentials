<?php

namespace Vortechron\Essentials\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class Install extends Command
{
    protected $packages = [
        "@estudioliver/vue-uuid-v4" => "^1.0.0",
        "bootstrap-vue" => "^2.0.0-rc.11",
        "form-backend-validation" => "^2.3.3",
        "moment" => "^2.22.2",
        "moment-timezone" => "^0.5.21",
        "slugify" => "^1.3.1",
        "sweetalert2" => "^7.28.4",
        "tailwindcss" => "^0.6.6",
        "v-money" => "^0.8.1",
        "validator" => "^10.8.0",
        "vue-avatar" => "^2.1.6",
        "vue-element-loading" => "^1.0.4",
        "vue-flatpickr-component" => "^7.0.6",
        "vue-form-generator" => "^2.3.1",
        "vue-multiselect" => "^2.1.3",
        "vue-overdrive" => "0.0.12",
        "vue-social-sharing" => "^2.3.3",
        "vue-wait" => "^1.3.2",
        "vue2-transitions" => "^0.2.3",
        "vuedraggable" => "^2.16.0"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'essentials:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install stuff that make web development easier, go to README for more info.';

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
        if ($this->confirm('Do you want to modify package.json?')) {
            $this->modifyPackageJSON();
            $this->info('Successfully modify package.json.');
        }

        if ($this->confirm('Do you want to publish necessary assets?')) {
            $this->publishAssets();
            $this->info('Successfully publish assets.');
        }

        if ($this->confirm('Do you want to modify webpack.mix.js?')) {
            $this->modifyWebpackMixJs();
            $this->info('Successfully modify webpack.mix.js');
        }
    }

    protected function modifyPackageJSON()
    {
        $jsonString = file_get_contents(base_path('package.json'));
        $data = json_decode($jsonString, true);

        foreach ($this->packages as $key => $entry) {
            $data['devDependencies'][$key] = $entry;
        }

        $newJsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(base_path('package.json'), $newJsonString);
    }

    protected function publishAssets()
    {
        $from = __DIR__ .'/../../resources/assets';
        $to = base_path('resources/vortechron/laravel-essentials');

        (new Filesystem)->copyDirectory($from, $to);
    }

    protected function modifyWebpackMixJs()
    {
        $string = "mix.js('resources/vortechron/laravel-essentials/js/app.js', 'public/js')
        .sass('resources/vortechron/laravel-essentials/sass/app.scss', 'public/css');";

        $filesystem = Storage::createLocalDriver([
            'driver' => 'local',
            'root' => base_path()
        ]);

        $filesystem->append('webpack.mix.js', $string);
    }
}
