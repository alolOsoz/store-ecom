<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandController extends Controller
{

    public function index()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(PAGENATE);
        return view('dashboard.brands.index', compact('brands'));

    }

    public function create()
    {
        return view('dashboard.brands.create');

    }


    public function store(BrandRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else
                $request->request->add(['is_active' => 1]);

            $filename = "";
            if ($request->has('photo')) {
                $filename = uploadImage('brands', $request->photo);
            }
            $brand = Brand::create($request->except('_token', 'photo'));
            $brand->name = $request->name;
            $brand->photo = $filename;
            $brand->save();

            DB::commit();
            return redirect()->route('admin.brands')->with(['success' => __('messages.success')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.brands')->with(['error' => __('messages.error')]);

        }
    }


    public function edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand)
            return redirect()->route('admin.brands')->with(['error' => __('admin/brand.exists')]);

        return view('dashboard.brands.edit', compact('brand'));

    }

    public function update($id, BrandRequest $request)
    {
        try {




            $brand = Brand::find($id);
            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => __('admin/brand.exists')]);

            DB::beginTransaction();

            if ($request->has('photo')) {
                $image = Str::after($brand->photo, 'assets/');
                $image = base_path('public/assets/' . $image);
                unlink($image); //delete from folder

                $filename = uploadImage('brands', $request->photo);
                Brand::where('id',$id)->update(['photo'=>$filename]);
            }

            if (!$request->has('is_active')) {
                $request->request->add(['is_active' => 0]);
            } else
                $request->request->add(['is_active' => 1]);


            $brand->update($request->except('_token','id','photo'));
            $brand->name = $request->name;
            $brand->save();


            DB::commit();

            return redirect()->route('admin.brands')->with(['success' => __('messages.success')]);


        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            return redirect()->route('admin.brands')->with(['error' => __('messages.error')]);

        }
    }

    public function delete($id)
    {
        try {
            $brand = Brand::find($id);
            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => __('admin/brand.exists')]);

            $image = Str::after($brand->photo, 'assets/');
            $image = base_path('public/assets/' . $image);
            unlink($image); //delete from folder

            $brand->translations()->delete();

            $brand->delete();
            return redirect()->route('admin.brands')->with(['success' => __('messages.success')]);

        } catch (\Exception $e) {
            return redirect()->route('admin.brands')->with(['error' => __('messages.error')]);

        }
    }

    public function changestatus()
    {

    }


}
