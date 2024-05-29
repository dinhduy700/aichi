<?php

namespace App\Http\Repositories;

use App\Models\MMenu;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyMenuRepository
{
    public function getListMenuByUserCD($userCD)
    {
        return MMenu::query()->where('user_cd', $userCD)->first()?->toArray();
    }

    public function update($userCd, $data)
    {
        DB::beginTransaction();
        try {
            $menu = MMenu::where([
                ['user_cd', '=', $userCd],
            ])->first();
            if ($menu) {
                $menu->update($data);
            } else {
                $data['user_cd'] = Auth::id();
                MMenu::create($data);
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

    public function delete($userCd)
    {
        DB::beginTransaction();
        try {
            MMenu::where([
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