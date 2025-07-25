<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Crea un nuevo Service Class dentro de App/Services';

    public function handle()
    {
        $name = $this->argument('name');

        // Asegurarse de que no se duplique el sufijo Service
        $className = Str::studly(str_replace('Service', '', $name)) . 'Service';
        $namespace = 'App\\Services';
        $path = app_path('Services/' . $className . '.php');

        // Crear carpeta si no existe
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        // Evitar sobrescribir
        if (File::exists($path)) {
            $this->error("El servicio '$className' ya existe.");
            return Command::FAILURE;
        }

        // Plantilla base (stub)
        $stub = <<<EOT
<?php

namespace $namespace;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class $className
{
    /**
     * Obtener recursos paginados.
     */
    public function getAllPaginated(int \$perPage = 10)
    {
        // return Model::with(...)->latest()->paginate(\$perPage);
    }

    /**
     * Buscar recursos por término.
     */
    public function search(string \$query, int \$perPage = 10)
    {
        // return Model::where(...)->paginate(\$perPage);
    }

    /**
     * Crear un nuevo recurso.
     */
    public function create(array \$data)
    {
        // \$model = new Model(\$data);
        // \$model->user_id = Auth::id();
        // \$model->save();
        // return \$model;
    }

    /**
     * Actualizar un recurso existente.
     */
    public function update(\$model, array \$data)
    {
        // \$model->fill(\$data);
        // \$model->save();
        // return \$model;
    }

    /**
     * Eliminar un recurso.
     */
    public function delete(\$model)
    {
        // \$model->delete();
    }

    /**
     * Guardar imagen en el storage.
     */
    protected function storeImage(\$image): string
    {
        // \$name = Str::uuid() . '.' . \$image->getClientOriginalExtension();
        // return \$image->storeAs('folder', \$name, 'public');
    }

    /**
     * Eliminar imagen del storage.
     */
    protected function deleteImage(?string \$path): void
    {
        if (\$path && Storage::disk('public')->exists(\$path)) {
            Storage::disk('public')->delete(\$path);
        }
    }
}
EOT;

        File::put($path, $stub);

        $this->info("Servicio creado: App\\Services\\$className");
        return Command::SUCCESS;
    }
}
