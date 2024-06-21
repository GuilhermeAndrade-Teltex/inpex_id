<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RestoreBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restaura o backup a partir de arquivos SQL em um diretório';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = storage_path('app\dumps_mysql');
        if (!Storage::exists($directory)) {
            $this->error("O diretório '$directory' não existe.");
            return;
        }

        $sqlFiles = Storage::files($directory);

        foreach ($sqlFiles as $file) {
            $sql = Storage::get($file);

            try {
                DB::unprepared($sql);
                $this->info("Arquivo '$file' restaurado com sucesso.");
            } catch (\Exception $e) {
                $this->error("Erro ao restaurar o arquivo '$file': " . $e->getMessage());
            }
        }

        $this->info("Backup restaurado com sucesso!");
    }
}
