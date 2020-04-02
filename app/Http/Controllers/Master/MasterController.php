<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Redirect;
use Request;

class MasterController extends Controller {
    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('menu_permission');
    }

    public function index(Request $request) {
        $masterFormDatas = DB::select(DB::raw("
                            SELECT * FROM sys_master_entry_details
                            WHERE sys_master_entry_name=" . "'$request->sys_master_entry_name'
                            AND status=" . "'Active'"));
        $html = view('Master.masterEntry', compact('masterFormDatas'));
        echo $html;
    }

    public function masterDataStore(Request $request) {
        $requests = array_merge(
            $request->except('tableName', '_token', 'updated_at', 'pkId', 'primaryKey'),
            array(
                'created_at' => date('Y-m-d'),
                'created_by' => Auth::id(),
            )
        );
        if ($request->primaryKey > 0) {
            DB::table($request->tableName)->where($request->tableName . '_id', '=', $request->primaryKey)
                ->update($requests);
        } else {
            DB::table($request->tableName)->insert($requests);
        }
        return Redirect::to($request->tableName)->with('message', 'Data successfully save');
    }

    public function getModuleRecord(Request $request) {
        $masterFormDatas = DB::select(DB::raw("
            SELECT * FROM sys_master_entry_details
            WHERE sys_master_entry_name=" . "'$request->sys_master_entry_name'
            AND status=" . "'Active'"));
        $getRecord = DB::select(DB::raw("SELECT * FROM " . $request->sys_master_entry_name . "s
            WHERE " . $request->sys_master_entry_name . "s_id=" . $request->pkId));
        $html = view("Master.masterEntry", compact('masterFormDatas', 'getRecord'));
        echo $html;
    }

    public function deleteModuleRecord(Request $request) {
        $table = $request->sys_master_entry_name . 's';
        $primaryId = $request->sys_master_entry_name . 's_id';
        $deleteIds = explode(',', $request->pkId);
        foreach ($deleteIds as $deleteId) {
            DB::table($table)->where($primaryId, '=', $deleteId)->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Data Successfully Deleted']);
    }

    public function getModuleRecordForView(Request $request) {
        $masterFormDatas = DB::select(DB::raw("SELECT * FROM sys_master_entry_details
            WHERE sys_master_entry_name=" . "'$request->sys_master_entry_name'
            AND status=" . "'Active'"));
        $getRecord = DB::select(DB::raw("SELECT * FROM " . $request->sys_master_entry_name . "s
            WHERE " . $request->sys_master_entry_name . "s_id=" . $request->pkId));
        $html = view('Master.view', compact('masterFormDatas', 'getRecord'));
        echo $html;
    }
}

