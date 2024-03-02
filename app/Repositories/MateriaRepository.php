<?php


namespace App\Repositories;

use App\Models\Materia;
use App\Repositories\Contracts\MateriaRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class MateriaRepository implements MateriaRepositoryInterface
{
    protected $model;

    public function __construct(Materia $materia)
    {
        $this->model = $materia;
    }

    public function getAll()
    {
        return $this->model
            ->select('id', 'titulo', 'descricao', 'imagem', 'data_de_publicacao')
            ->orderBy('created_at', 'asc')
            ->paginate();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $materia = $this->findById($id);
        $materia->update($data);
        return $materia;
    }

    public function delete($id)
    {
        $materia = $this->findById($id);

        if (!$materia) {
            return false;
        }

        if ($materia->imagem) {
            $imagemPath = str_replace('/storage/', '', $materia->imagem);

            Storage::disk('public')->delete($imagemPath);
        }

        return $materia->delete();
    }
}
