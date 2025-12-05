<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchController extends Controller
{
    public function searchAjax(Request $request)
    {
        // Валидация входного параметра
        $request->validate([
            'search' => 'nullable|string|max:100'
        ]);

        // Базовый запрос с жадной загрузкой
        $query = User::query()->with('image.socset');

        // Если есть поисковый запрос - фильтруем
        if ($request->filled('search')) {
            $searchTerm = addcslashes($request->get('search'), '%_');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Пагинация результатов (по 20 на страницу)
        $users = $query->paginate(20);

        return response()->json($users);
    }

    // public function searchAjax(Request $request)
    // {
    //     if ($request->has('search') && $request->get('search') != '') {
    //         $users = User::query()
    //             ->with('image')
    //             ->where('name', 'like', '%' . $request->get('search') . '%')
    //             ->orWhere('email', 'like', '%' . $request->get('search') . '%')
    //             ->get();

    //         return response()->json($users);
    //     } elseif ($request->has('search') && is_null($request->get('search'))) {
    //         $users = User::query()
    //             ->with('image.socset')
    //             ->get();

    //         foreach ($users as $user) {
    //             $user->image->socset;
    //         }
    //         return response()->json($users);
    //     }
    //     throw new BadRequestHttpException();
    // }

    public function search()
    {
        return view('search');
    }
}
