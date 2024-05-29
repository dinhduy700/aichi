<?php

namespace App\Http\Controllers\MyMenu;

use App\Http\Controllers\Controller;
use App\Http\Repositories\MyMenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyMenuController extends Controller
{
    protected $myMenuRepository;

    public function __construct(
        MyMenuRepository $myMenuRepository
    )
    {
        $this->myMenuRepository = $myMenuRepository;
    }

    public function index(Request $request)
    {
        $mode = $request->input('mode');
        $defaultMenus = include app_path('Helpers/Menu.php');
        $userCD = Auth::id();
        $listMenu = $this->myMenuRepository->getListMenuByUserCD($userCD);
        return view('my-menu', compact('listMenu', 'mode', 'defaultMenus'));
    }

    public function saveMenus(Request $request)
    {
        $response = $this->myMenuRepository->update(Auth::id(), $request->all());
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.updated'));
        }
        return response()->json($response);
    }

    public function deleteMenu(Request $request)
    {

        $response = $this->myMenuRepository->delete(Auth::id());
        if ($response['status'] == 200) {
            session()->flash('success', __('messages.deleted'));
        }
        return response()->json($response);
    }
}