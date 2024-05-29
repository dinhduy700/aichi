<?php 
namespace App\Http\Repositories;

use App\Models\MUser;
use Illuminate\Http\Response;
use DB;

class UserRepository 
{
    public function getListWithTotalCount($request)
    {
        $list = MUser::select('*')->filter($request);
        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => applyOrderBy($list, [['user_cd']]),
        ];
    }

    public function getDetail($userCd) {
        $user = MUser::where([
            ['user_cd', '=', $userCd],
        ])->first();
        return $user;
    }

    public function store($data) {
        DB::beginTransaction();
        try {
            $user = MUser::create($data); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $user
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    public function update($userCd, $data) {
        DB::beginTransaction();
        try {
            $user = MUser::where([
                ['user_cd', '=', $userCd],
            ])->first();

            if ($user) {
                $user->update($data);
            }
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    public function delete($userCd) {
        DB::beginTransaction();
        try {
            MUser::where([
                ['user_cd', '=', $userCd], 
            ])->delete();
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }
}