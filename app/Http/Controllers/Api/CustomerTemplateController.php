<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerTemplate;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\CustomerTemplateRequest;
use App\Http\Resources\CustomerTemplateResource;
use Illuminate\Http\Request;
use DB;

class CustomerTemplateController extends BaseApiController
{
  
    public function customertemplate(Request $request)
    {
        try {
            $vm = CustomerTemplate::all();
            
            return $this->sendResponse(CustomerTemplateResource::collection($vm), 'Customer  Template fetched successfully');
           
        } catch (\Exception $e) {
            return $this->sendError('Server error!', $e->getMessage(), 500);
        }
    }

    public function postcustomertemplate(CustomerTemplateRequest $request)
{
    $validated = $request->validated();

    try {
        $customerTemplate = CustomerTemplate::create([
            'code' => $request->code,
            'description' => $request->description,
            'contact_type' => $request->contact_type,
            'IsBCToPortalIntegrated' => $request->get('IsBCToPortalIntegrated', false),
            'BCToPortalIntegratedTime' => $request->get('BCToPortalIntegratedTime'),
            'IsPortalToBCIntegrated' => $request->get('IsPortalToBCIntegrated', false),
            'PortalToBCIntegratedTime' => $request->get('PortalToBCIntegratedTime'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer Template created successfully.',
            'data' => new CustomerTemplateResource($customerTemplate),
        ], 201);

    } catch (\Exception $e) {
        return $this->sendError('Server error!', $e->getMessage(), 500);
    }
}

    
    public function destroy($id)
    {
      
        $customertemplate = CustomerTemplate::find($id);
    
        if (!$customertemplate) {
          
            return response()->json([
                'message' => 'Template not found.'
            ], 404);
        }
    
      
        $customertemplate->delete();
    
       
        return response()->json([
            'message' => 'Template successfully deleted.'
        ], 200);
    }

    
    public function updatecustomertemplate(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:customer_templates,id',
            'code' => 'required|string',
            'description' => 'required|string',
            'contact_type' => 'nullable|string',
            'IsBCToPortalIntegrated' => 'required|boolean',
            'BCToPortalIntegratedTime' => 'nullable|date',
            'IsPortalToBCIntegrated' => 'required|boolean',
            'PortalToBCIntegratedTime' => 'nullable|date',
        ]);
    
        DB::beginTransaction();
    
        try {
            $template = CustomerTemplate::findOrFail($validated['template_id']);
    
            $templateData = [
                'code' => $validated['code'],
                'description' => $validated['description'],
                'contact_type' => $validated['contact_type'],
                'IsBCToPortalIntegrated' => $validated['IsBCToPortalIntegrated'],
                'BCToPortalIntegratedTime' => $validated['BCToPortalIntegratedTime'],
                'IsPortalToBCIntegrated' => $validated['IsPortalToBCIntegrated'],
                'PortalToBCIntegratedTime' => $validated['PortalToBCIntegratedTime'],
                'isModified' => true,
            ];
    
            $template->update($templateData);
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'Template Data Updated Successfully',
                'data' => new CustomerTemplateResource($template),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => 'Failed to update data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

}