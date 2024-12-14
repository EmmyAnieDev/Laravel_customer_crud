<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\File;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::orderBy('id', $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC')->get();  // Order by descending (latest ID).

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        $customer = new Customer();

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = '/uploads/'.$fileName;

            $customer->image = $filePath;
        }

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->bank_account_number = $request->bank_account_number;
        $customer->about = $request->about;

        $customer->save();

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerStoreRequest $request, string $id)
    {
        $customer =  Customer::findOrFail($id);

        if ($request->hasFile('image')) {

            File::delete(public_path($customer->image));  // delete previous image

            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = '/uploads/'.$fileName;

            $customer->image = $filePath;
        }

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->bank_account_number = $request->bank_account_number;
        $customer->about = $request->about;

        $customer->save();

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete(); // This will now soft delete the customer due to the SoftDeletes trait in the model.

        return redirect()->route('customers.index');
    }

    public function search(Request $request)
    {
        $param = $request->search;

        $customers = Customer::where('first_name', 'LIKE', '%' . $param . '%')
                     ->orWhere('last_name', 'LIKE', '%' . $param . '%')
                     ->orWhere('email', 'LIKE', '%' . $param . '%')
                     ->orderBy('id', $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC')->get(); 

        return view('customer.index', compact('customers'));
    }

    public function trash(Request $request)
    {
        $customers = Customer::orderBy('id', $request->has('order') && $request->order == 'asc' ? 'ASC' : 'DESC')->onlyTrashed()->get();  

        return view('customer.trash', compact('customers'));
    }

    /**
     * Restore specified resource from trash to index
     */
    public function restore(string $id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        $customer->restore();

        return redirect()->back();
    }

    /**
     * Force delete specified resource from trash
     */
    public function forceDestroy(string $id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        File::delete(public_path($customer->image));

        $customer->forceDelete();

        return redirect()->back();
    }
}
