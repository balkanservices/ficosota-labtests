<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductFileRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\AnalysisLimits;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$products = Product::where('deleted', 0)
                ->orderBy('id', 'desc')
                ->paginate(self::PAGE_SIZE);

        return view('products/index', [
			'products' => $products,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
		$product = Product::find($id);

        $analysisLimitsOptions = AnalysisLimits::getOptions();
        $typeOptions = Product::getTypeOptions();

        if ($product->deleted) {
            return redirect()->route('products.index');
        }

        return view('products/edit', [
			'product' => $product,
            'analysisLimitsOptions' => $analysisLimitsOptions,
            'typeOptions' => $typeOptions,
		]);
    }


    /**
     * @return \Illuminate\Http\Response
     */
    public function newProduct()
    {
        $product = new Product([
            'brand' => __('products.name_new'),
        ]);

        $product->save();

        return redirect()->route('products.edit', ['id' => $product->id]);
    }

    public function process(ProductRequest $request, $locale, $id)
    {

        $product = Product::find($id);

        $validator = Validator::make($request->all(), [
            'diaper_count_in_package' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.edit', ['id' => $product->id])
                ->withErrors($validator)
                ->withInput();
        }

        if(empty($product)) {
            $product = new Product($request->all());
            $product->save();
        } else {
            $product->update($request->all());
        }

        $product->autodetectMachine();
        $product->update();

        if(!empty($request->get('save_and_create_recipe'))) {
            return redirect()->route('recipes.new', ['productId' => $product->id]);
        }

        $request->session()->flash('status', __('products.saved'));
        return redirect()->route('products.edit', ['id' => $product->id]);
    }

    public function delete(ProductRequest $request, $locale, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->recipes->isEmpty() || !$product->samplesLists->isEmpty()) {
            return redirect()->route('products.index');
        }

        $product->deleted = 1;
        $product->save();

        $request->session()->flash('status', __('products.deleted'));

        return redirect()->route('products.index');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return view('products/file_upload');
    }

    public function processFileUpload(ProductFileRequest $request, $locale)
    {
        $file = $request->file('file');

        $productsImported = 0;
        $sheets = Excel::load($file->getRealPath(), function($reader) {})->get();

        foreach($sheets as $sheet) {
            $productsImported += Product::processExcelSheet($sheet);

        }

        $request->session()->flash('status', __('products.imported') . ' ' . $productsImported);

        return redirect()->route('products.index');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function audit($locale, $id)
    {
		$product = Product::findOrFail($id);

        $auditLog = $product->audits()->with('user')->orderBy('id', 'DESC')->get();

        return view('products/audit', [
			'product' => $product,
            'auditLog' => $auditLog,
		]);
    }

}
