<?php

namespace App\Console\Commands;

use App\Http\Controllers\DadosApiBec;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Request;

class CarregarDadosOcsDaApiParaBancoDeDados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hackathonBEC:carregar-dados-ocs-banco-de-dados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dada uma lista de ocs o script faz a busca na API do portal BEC e carrega as informações no banco de dados usado pela aplicação.';

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

        $ocsFile = file_get_contents('/Users/joaopaulodunder/projects/hackaton-bec/ocs-xml-list.txt');
        $ocs = explode(';', $ocsFile);

       foreach ($ocs as $oc) {
           DadosApiBec::getDataOcBec($oc);
       }

    }
}
