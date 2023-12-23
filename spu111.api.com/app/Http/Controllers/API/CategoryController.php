<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    function getAll() {
        $list = Categories::all();
        return response()->json($list, 200, ['Charset'=>'utf-8']);
    }

    function create(Request $request) {
        $input = $request->all(); // отримуємо інпути
        $image = $request->file("image"); // отримуємо фото
        $manager = new ImageManager(new Driver()); // менеджер фото
        $imageName = uniqid().".webp";
        $sizes = [50, 150, 300, 600, 1200];
        foreach ($sizes as $size) {
            $imageSave = $manager->read($image);
            $imageSave->scale(width:$size);
            $path=public_path("upload/".$size."_".$imageName);
            $imageSave->toWebp()->save($path);
        }
        $input["image"]=$imageName;
        $category = Categories::create($input);
        return response()->json($category, 200, ['Charset'=>'utf-8']);
    }

    // Task 1
    // Update function with validations
    function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400, ['Charset' => 'utf-8']);
        }

        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found!'], 404, ['Charset' => 'utf-8']);
        }

        $input = $request->all();

        // Check for provided image
        if ($request->hasFile('image')) {
            $image = $request->file("image");
            $manager = new ImageManager(new Driver());
            $imageName = uniqid().".webp";
            $sizes = [50, 150, 300, 600, 1200];
            foreach ($sizes as $size) {
                $imageSave = $manager->read($image);
                $imageSave->scale(width:$size);
                $path = public_path("upload/".$size."_".$imageName);
                $imageSave->toWebp()->save($path);
            }
            $input["image"] = $imageName;
        }

        $category->update($input);

        return response()->json($category, 200, ['Charset' => 'utf-8']);
    }

    // Delete function
    function delete($id) {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found!'], 404, ['Charset' => 'utf-8']);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully!'], 200, ['Charset' => 'utf-8']);
    }
}
