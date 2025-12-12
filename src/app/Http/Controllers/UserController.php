<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\DMC\UserCreateDMC;
use App\DMC\UserDeleteDMC;
use App\DMC\UserUpdateDMC;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

use App\Processes\UserCreateProcess;
use App\Processes\UserDeleteProcess;
use App\Processes\UserListProcess;
use App\Processes\UserShowProcess;
use App\Processes\UserUpdateProcess;

class UserController extends Controller {
    public function __construct(
        private readonly UserListProcess $listProcess,
        private readonly UserShowProcess $showProcess,
        private readonly UserCreateProcess $createProcess,
        private readonly UserUpdateProcess $updateProcess,
        private readonly UserDeleteProcess $deleteProcess,
    ) {}

    /**
     * Responsável por retornar o response de todos os usuários
     * @return JsonResponse - response de todos os usuários com paginação
     */
    public function index(Request $request): JsonResponse {
        $perPage = $request->integer('per_page', 10);
        $users = $this->listProcess->exec($perPage);

        return response()->json([
            'success' => true,
            'data'    => $users->items(),
            'meta'    => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    /**
     * Responsável por retornar o response do detalhe de um usuário pelo id
     * @param int $id - id do usuário
     * @return JsonResponse - response do detalhe de usuário
     */
    public function show(int $id): JsonResponse {
        $user = $this->showProcess->exec($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Responsável por criar um usuário e retornar a response de criação
     * @param UserStoreRequest $request - payload de criação de usuário
     * @return JsonResponse - response de criação de usuário
     */
    public function store(UserStoreRequest $request): JsonResponse {
        $dmc = UserCreateDMC::fromArray($request->validated());
        $user = $this->createProcess->exec($dmc);

        return response()->json([
            'success' => true,
            'message' => 'Usuário criado com sucesso.',
            'data' => $user,
        ], 201);
    }

    /**
     * Responsável por atualizar um usuário e retornar a response de atualização
     * @param UserUpdateRequest $request - payload de atualização de usuário
     * @param int $id - id do usuário que vai ser alterado
     * @return JsonResponse - response de atualização de usuário
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse {
        $dmc = UserUpdateDMC::fromArray($id, $request->validated());
        $user = $this->updateProcess->exec($dmc);

        return response()->json([
            'success' => true,
            'message' => 'Usuário atualizado com sucesso.',
            'data' => $user,
        ]);
    }

    /**
     * Responsável por excluir um usuário e retornar sua response
     * @param int $id - id do usuário que será excluído
     * @return JsonResponse - response de exclusão de usuário
     */
    public function destroy(int $id): JsonResponse {
        $dmc = UserDeleteDMC::fromId($id);
        $this->deleteProcess->exec($dmc);

        return response()->json([
            'success' => true,
            'message' => 'Usuário deletado com sucesso.',
        ]);
    }
}
