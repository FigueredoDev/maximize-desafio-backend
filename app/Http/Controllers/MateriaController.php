<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMateriaRequest;
use App\Http\Requests\UpdateMateriaRequest;
use App\Http\Resources\MateriaResource;
use App\Repositories\Contracts\MateriaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriaController extends Controller
{

    protected $materiaRepository;

    public function __construct(MateriaRepositoryInterface $materiaRepository)
    {
        $this->materiaRepository = $materiaRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $materias = $this->materiaRepository->getAll();

            return MateriaResource::collection($materias);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar as matérias.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMateriaRequest $request)
    {
        try {
            if ($request->hasFile('imagem')) {
                $imagem = $request->file('imagem');
                $imagemPath = $imagem->store('imagens', 'public');

                $validatedData = $request->validated();
                $validatedData['imagem'] = Storage::url($imagemPath);
            }

            $materia = $this->materiaRepository->create($validatedData);

            return new MateriaResource($materia);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['message' => 'Erro ao criar a matéria.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $materia = $this->materiaRepository->findById($id);

            if (!$materia) {
                return response()->json(['message' => 'Matéria não encontrada.'], 404);
            }

            return new MateriaResource($materia);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Matéria não encontrada.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMateriaRequest $request, string $id)
    {
        try {
            $materia = $this->materiaRepository->findById($id);

            if (!$materia) {
                return response()->json(['message' => 'Matéria não encontrada.'], 404);
            }

            $data = $request->validated();

            $updatedMateria = $this->materiaRepository->update($id, $data);

            return new MateriaResource($updatedMateria);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar a matéria.'], 500);
        }
    }

    /**
     * Update the image for a specific resource.
     *
     * @param Request $request The request object
     * @param string $id The identifier of the resource
     */
    public function updateImage(Request $request, $id)
    {
        try {
            $request->validate(['imagem' => 'required|image|max:10240']);

            $materia = $this->materiaRepository->findById($id);

            if (!$materia) {
                return response()->json(['message' => 'Matéria não encontrada.'], 404);
            }

            if ($request->hasFile('imagem')) {
                if ($materia->imagem) {
                    $oldImagePath = str_replace('/storage/', '', $materia->imagem);
                    Storage::disk('public')->delete($oldImagePath);
                }

                $newImagePath = $request->file('imagem')->store('imagens', 'public');

                $materia->imagem = 'storage/' . $newImagePath;
                $materia->save();

                return new MateriaResource($materia);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Nenhuma imagem fornecida.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->materiaRepository->delete($id);

            if (!$deleted) {
                return response()->json(['message' => 'Matéria não encontrada.'], 404);
            }

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir a matéria.'], 500);
        }
    }
}
