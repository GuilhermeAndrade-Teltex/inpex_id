<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\Student;
use Faker\Core\DateTime;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use App\Models\CorsightQueue;
use Illuminate\Support\Str;

class ImportExcel extends Command
{
    protected $signature = 'import:excel {file}';
    protected $description = 'Importar dados de uma planilha Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $file = $this->argument('file');

        // Verifica se o arquivo existe
        if (!file_exists($file)) {
            $this->error("O arquivo {$file} não existe.");
            return 1;
        }

        $this->info("Carregando o arquivo: {$file}");

        try {
            $spreadsheet = IOFactory::load($file);
        } catch (\Exception $e) {
            $this->error("Erro ao carregar o arquivo: " . $e->getMessage());
            return 1;
        }

        $sheet = $spreadsheet->getActiveSheet();
        $sheetName = $sheet->getTitle();
        $rows = $sheet->toArray();

        // Abrir arquivo de log para escrita
        $logFile = storage_path("logs/{$sheetName}.txt");
        $logHandle = fopen($logFile, 'a');

        try {
            foreach ($rows as $index => $row) {
                if ($index != 0) {

                    $this->info("Processando linha {$index}: " . implode(', ', $row));

                    // data de nascimento
                    $dateOfBirth = $row[4];
                    $dateTime = \DateTime::createFromFormat('d/m/Y', $dateOfBirth);
                    if ($dateTime) {
                        $formattedDateOfBirth = $dateTime->format('Y-m-d') . ' 00:00:00';
                    } else {
                        $this->warn("Formatação da data de nascimento incorreta {$index}, ignorando registro.");
                        fwrite($logHandle, "Linha {$index}: Data de nascimento do aluno incorreta.\n");
                        continue;
                    }

                    // telefone do responsável
                    $responsible_phone = $row[7];
                    $responsible_phone = preg_replace('/[()\-\s]/', '', $responsible_phone);

                    // endereço do aluno
                    if ($row[5] != null) {
                        $address = explode('-', $row[5]);
                        if (is_array($address)) {

                            $street = $address[0];
                            $number = $address[1];
                            $neighborhood = $address[2];
                            $city = $address[3];
                            $state = $address[4];
                            $zip = $address[5];
                        } else {
                            $street = '';
                            $number = '';
                            $neighborhood = '';
                            $city = '';
                            $state = '';
                            $zip = '';
                        }
                    }

                    if (!School::where('name', $row[2])->exists()) {
                        $school = School::create([
                            'client_id' => 1, // Alterar quando houver mais clientes.
                            'name' => $row[2],
                            'regional' => $sheetName,
                            'city' => $row[1],
                            'responsible' => '',
                            'cep' => '',
                            'address' => '',
                            'number' => '',
                            'district' => '',
                            'state' => ''
                        ]);

                        $schoolId = str_pad($school->id, 6, '0', STR_PAD_LEFT);
                        $schoolName = $schoolId . '_' . $school->name;
                        $slug = Str::slug($schoolName, '_');

                        $data = [
                            'watchlist_type' => 'whitelist',
                            'display_name' => $slug,
                            'display_color' => '#00aa00',
                            'watchlist_notes' => [
                                'free_notes' => "This watchlist was created through InpexID integration at " . now()->format('d/m/Y - H:i:s') . ".",
                            ],
                        ];

                        CorsightQueue::create([
                            'status' => 'NOT_SEND',
                            'module_id' => $school->id,
                            'module' => 'corsight_watchlist',
                            'data' => json_encode($data),
                            'endpoint' => 'addWatchlist',
                            'log' => '',
                        ]);
                    }

                    // validação campo cpf do aluno
                    $cpf_student = $row[9];
                    if (empty($cpf_student)) {
                        $this->warn("CPF do aluno vazio na linha {$index}, ignorando registro.");
                        fwrite($logHandle, "Linha {$index}: CPF do aluno vazio.\n");
                        continue;
                    }

                    if (Student::where('cpf', $cpf_student)->exists()) {
                        $this->warn("CPF do aluno duplicado na linha {$index}, ignorando registro.");
                        fwrite($logHandle, "Linha {$index}: CPF do aluno duplicado.\n");
                        continue;
                    }

                    Student::create([
                        'school_id' => $school->id,
                        'name' => $row[3],
                        'cpf' => $row[9],
                        'responsible_name' => $row[6],
                        'responsible_phone' => $responsible_phone,
                        'cpf_responsible' => $row[8],
                        'date_of_birth' => $formattedDateOfBirth,
                        'class' => $row[10],
                        'grade' => $row[11],
                        'cep' => $zip,
                        'address' => $street,
                        'number' => $number,
                        'district' => $neighborhood,
                        'city' => $city,
                        'state' => $state
                    ]);
                    $this->info("Linha {$index} importada com sucesso.");
                }
            }
        } catch (\Exception $e) {
            $this->error("Erro ao processar a linha {$index}: " . $e->getMessage());
            fwrite($logHandle, "Linha {$index}: Erro ao cadastrar aluno.\n");
        }

        $this->info('Dados importados com sucesso!');
        return 0;
    }
}
