<?php

namespace App\Http\Controllers;

use App\Models\MenuVisibility;
use App\Helpers\MenuHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuVisibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $allMenus = MenuHelper::getAllIndividualMenuItems();
        
        // Get user's menu visibility preferences
        $menuVisibilities = MenuVisibility::where('user_id', $user->id)
            ->pluck('is_hidden', 'menu_key')
            ->toArray();

        // Group menus by section
        $menusBySection = [];
        foreach ($allMenus as $key => $menu) {
            $section = $menu['section'] ?? 'Lainnya';
            if (!isset($menusBySection[$section])) {
                $menusBySection[$section] = [];
            }
            $menusBySection[$section][$key] = $menu;
        }

        return view('menu-visibility.index', compact('menusBySection', 'menuVisibilities'));
    }

    /**
     * Update menu visibility
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $menuKey = $request->input('menu_key');
        $isHidden = $request->input('is_hidden', false);

        // Validate menu_key exists in MenuHelper
        $allMenus = MenuHelper::getAllIndividualMenuItems();
        $validMenuKeys = array_keys($allMenus);
        
        if (!in_array($menuKey, $validMenuKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'Menu key tidak valid'
            ], 400);
        }

        // Update or create menu visibility
        MenuVisibility::updateOrCreate(
            [
                'user_id' => $user->id,
                'menu_key' => $menuKey,
            ],
            [
                'is_hidden' => $isHidden,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Visibility menu berhasil diperbarui'
        ]);
    }

    /**
     * Toggle menu visibility
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $menuKey = $request->input('menu_key');

        // Validate menu_key exists in MenuHelper
        $allMenus = MenuHelper::getAllIndividualMenuItems();
        $validMenuKeys = array_keys($allMenus);
        
        if (!in_array($menuKey, $validMenuKeys)) {
            return response()->json([
                'success' => false,
                'message' => 'Menu key tidak valid'
            ], 400);
        }

        // Get or create menu visibility
        $menuVisibility = MenuVisibility::firstOrCreate(
            [
                'user_id' => $user->id,
                'menu_key' => $menuKey,
            ],
            [
                'is_hidden' => false,
            ]
        );

        // Toggle
        $menuVisibility->toggle();

        return response()->json([
            'success' => true,
            'is_hidden' => $menuVisibility->is_hidden,
            'message' => 'Visibility menu berhasil diubah'
        ]);
    }
}
