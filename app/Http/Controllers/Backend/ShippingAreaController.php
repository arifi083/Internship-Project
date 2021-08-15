<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Carbon\Carbon;

class ShippingAreaController extends Controller
{
    public function DivisionView(){

        $divisions = ShipDivision::orderBy('id','DESC')->get();
        return view('backend.ship.division.view_division',compact('divisions'));
    }



    public function DivisionStore(Request $request){
        $request->validate([
    		'division_name' => 'required',
           
    		
    	]);

        ShipDivision::create([
            'division_name' => $request->division_name,
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'Division Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    }  //end method


    public function DivisionEdit($id){
        $divisions = ShipDivision::findOrFail($id);
        return view('backend.ship.division.edit_division',compact('divisions'));
    }




    public function DivisionUpdate(Request $request,$id){

        ShipDivision::findOrFail($id)->update([
            'division_name' => $request->division_name,
            'created_at' => Carbon::now(),
            

        ]);
        $notification = array(
            'message' => 'Division Updated Successfully',
            'alert-type' => 'info'
        );         
        return Redirect()->route('manage-division')->with($notification);

    }  //end method



    public function DivisionDelete($id){

        ShipDivision::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Division Delete Successfully',
            'alert-type' => 'info'
        );   
        return Redirect()->back()->with($notification);


    } //end method








    //// Start Ship District

     public function DistrictView(){

        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::with('division')->orderBy('id','DESC')->get();
        //dd($districts);
        return view('backend.ship.district.view_district',compact('divisions','districts'));
    } //end method





    public function DistrictStore(Request $request){
        $request->validate([
    		'division_id' => 'required',
            'district_name' => 'required',
           
    		
    	]);

        ShipDistrict::create([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'District Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    }  //end method



    public function DistrictEdit($id){

        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::findOrFail($id);
        return view('backend.ship.district.edit_district',compact('district','division'));
    }



    public function DistrictUpdate(Request $request,$id){

        ShipDistrict::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
            'created_at' => Carbon::now(),
            

        ]);
        $notification = array(
            'message' => 'District Updated Successfully',
            'alert-type' => 'info'
        );         
        return Redirect()->route('manage-district')->with($notification);

    }  //end method



    public function DistrictDelete($id){

        ShipDistrict::findOrFail($id)->delete();
        $notification = array(
            'message' => 'District Delete Successfully',
            'alert-type' => 'info'
        );   
        return Redirect()->back()->with($notification);


    } //end method










     //// Start Ship State

     public function StateView(){

        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::orderBy('district_name','ASC')->get();
        $states = ShipState::with('division','district')->orderBy('id','DESC')->get();
        //dd($districts);
        return view('backend.ship.state.view_state',compact('divisions','districts','states'));
    } //end method



    public function StateStore(Request $request){
        $request->validate([
    		'division_id' => 'required',
            'district_id' => 'required',
            'state_name' => 'required',
    		
    	]);

        ShipState::create([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'State Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    }  //end method


    public function StateEdit($id){

        $division = ShipDivision::orderBy('division_name','ASC')->get();
        $district = ShipDistrict::orderBy('district_name','ASC')->get();
        $state = ShipState::findOrFail($id);
        return view('backend.ship.state.edit_state',compact('district','division','state'));
    }


    public function StateUpdate(Request $request,$id){

        ShipState::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
            'created_at' => Carbon::now(),
            

        ]);
        $notification = array(
            'message' => 'State Updated Successfully',
            'alert-type' => 'info'
        );         
        return Redirect()->route('manage-state')->with($notification);

    }  //end method



    public function StateDelete($id){

        ShipState::findOrFail($id)->delete();
        $notification = array(
            'message' => 'State Delete Successfully',
            'alert-type' => 'info'
        );   
        return Redirect()->back()->with($notification);


    } //end method








}
