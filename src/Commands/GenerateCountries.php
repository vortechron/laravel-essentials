<?php

namespace Vortechron\Essentials\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Countries\Package\Countries;
use PragmaRX\Countries\Package\Services\Config;

class GenerateCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'essentials:generate-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate List Of Countries and its states';

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
        $cc = new Countries(new Config([
            'hydrate' => [
                'elements' => [
                    'states' => true,
                ],
            ],
        ]));
    
        $results = $cc->all()
                ->map(function ($country) {
                    $commonName = $country->name->common;
    
                    return [
                        'name' => $commonName ,
                        'code' => $country->cca2,
                        'states' => $country->states->pluck('name')->values()->toArray()
                    ];
                })
                ->values()
                ->toArray();
    
        Storage::put('countries', json_encode($results));
    }
}
