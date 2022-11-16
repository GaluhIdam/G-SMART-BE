<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProspectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // public function attributes()
    // {
    //     return [
    //         'prospect_type_id' => '',
    //         'transaction_type_id' => '',
    //     ];
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // if (!$this->attributes->has('prospect_type_id')) {
        //     return false;
        // }

        // if (!$this->attributes->has('transaction_type_id')) {
        //     return false;
        // }

        $rules = [];

        $prospect_type = Request::input('prospect_type_id');
        $transaction_type = Request::input('transaction_type_id');

        if ($prospect_type == 1) {
            $prospect_rules = [
                'year' => 'required|date_format:Y',
                'ams_customer_id' => 'required|integer|exists:ams_customers,id',
            ];
        } else if ($prospect_type == 2) {
            $prospect_rules = [
                'year' => 'required|date_format:Y',
                'ams_customer_id' => 'required|integer|exists:ams_customers,id',
                'strategic_initiative_id' => 'required|integer|exists:strategic_initiatives,id',
                'pm_id' => 'required|integer|exists:users,id',
            ];
        }

        if (in_array($transaction_type, [1,2])) {
            $transaction_rules = [
                'tmb' => 'required|array',
                'tmb.*.product' => 'required|array',
                'tmb.*.product.*.product_id' => 'required|integer|exists:products,id',
                'tmb.*.product.*.aircraft_type.id' => 'required|integer|exists:ac_type_id,id',
                'tmb.*.product.*.component.id' => 'required|integer|exists:component_id,id',
                'tmb.*.product.*.apu.id' => 'required|integer|exists:apu_id,id',
                'tmb.*.product.*.engine.id' => 'required|integer|exists:engine_id,id',
                'tmb.*.product.*.market_share'  => 'required|numeric',
                'tmb.*.product.*.remark' => 'required|string',
                'tmb.*.product.*.maintenance_id.id' => 'required|integer|exists:maintenances,id',
            ];
        } else if ($transaction_type == 3) {
            $transaction_rules = [
                'pbth' => 'required|array',
                'pbth.*.product_id' => 'required|integer|exists:products,id',
                'pbth.*.aircraft_type_id' => 'required|integer|exists:ac_type_id,id',
                'pbth.*.target' => 'required|array',
                'pbth.*.target.*.month' => 'required|date_format:F',
                'pbth.*.target.*.rate' => 'required|numeric',
                'pbth.*.target.*.flight_hour' => 'required|numeric',
            ];
        }

        $rules = array_merge($prospect_rules, $transaction_rules);

        return $rules;
    }

    public function messages()
    {
        return [
            'year.required' => 'The Year field is required.',
            'year.date_format' => 'The Year format is invalid.',
            'ams_cuseromer_id.required' => 'The AMS & Area field is required.',
            'ams_cuseromer_id.exists' => 'The selected AMS & Area is invalid.',
            'strategic_initiative_id.required' => 'The Strategic Inititative field is required.',
            'strategic_initiative_id.exists' => 'The selected Strategic Inititative is invalid.',
            'pm_id.required' => 'The Project Manager field is required.',
            'pm_id.exists' => 'The selected Project Manager is invalid.',
            'tmb.required' => 'The TMB data collection is required.',
            'tmb.array' => 'The TMB data colection must be an array.',
            'tmb.*.product.required' => 'The Product data collection is required.',
            'tmb.*.product.array' => 'The Product data collection must be an array.',
            'tmb.*.product.*.product_id.required' => 'The Product field is required.',
            'tmb.*.product.*.product_id.exists' => 'The selected Product is invalid.',
            'tmb.*.product.*.aircraft_type.id.required' => 'The Aircraft Type field is required.',
            'tmb.*.product.*.aircraft_type.id.exists' => 'The selected Aircraft Type is invalid.',
            'tmb.*.product.*.component.id.required' => 'The Component field is required.',
            'tmb.*.product.*.component.id.exists' => 'The selected Component is invalid.',
            'tmb.*.product.*.apu.id.required' => 'The APU field is required.',
            'tmb.*.product.*.apu.id.exists' => 'The selected APU is invalid.',
            'tmb.*.product.*.engine.id.required' => 'The Engine field is required.',
            'tmb.*.product.*.engine.id.exists' => 'The selected Engine is invalid.',
            'tmb.*.product.*.market_share.required' => 'The Market Share field is required.',
            'tmb.*.product.*.market_share.numeric' => 'The Market Share field must be a number.',
            'pbth.required' => 'The PBTH data collection is required.',
            'pbth.array' => 'The PBTH data colection must be an array.',
            'pbth.*.product_id.required' => 'The Product field is required.',
            'pbth.*.product_id.exists' => 'The selected Product is invalid.',
            'pbth.*.aircraft_type_id.required' => 'The Aircraft Type field is required.',
            'pbth.*.aircraft_type_id.exists' => 'The selected Aircraft Type is invalid.',
            'pbth.*.target.required' => 'The Target data collection is required.',
            'pbth.*.target.array' => 'The Target data collection must be an array.',
            'pbth.*.target.*.month.required' => 'The Month field is required.',
            'pbth.*.target.*.month.date_format' => 'The Month format is invalid.',
            'pbth.*.target.*.rate.required' => 'The Rate field is required.',
            'pbth.*.target.*.rate.numeric' => 'The Rate field must be a number.',
            'pbth.*.target.*.flight_hour.required' => 'The Flight Hour field is required.',
            'pbth.*.target.*.flight_hour.numeric' => 'The Flight Houre field must be a number.',
        ];
    }
}
