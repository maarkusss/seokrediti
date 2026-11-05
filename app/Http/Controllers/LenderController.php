<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Settings;
use App\Services\LenderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class LenderController extends Controller
{
    /**
     * List All Lenders
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $lender_arr = LenderService::getAll();

        return response()->json($lender_arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $name
     * @return JsonResponse
     */
    public function update(Request $request, $name)
    {
        // First try exact match, then case-insensitive exact match, then LIKE '%name%' fallback
        $lender = Lenders::where('name', $name)->first()
            ?? Lenders::where('name', 'LIKE', $name)->first()
            ?? Lenders::where('name', 'LIKE', '%' . $name . '%')->first();

        if (empty($lender)) {
            return $this->getResponse(['message' => 'Lender not found!'], 404);
        }

        /** @var Lenders $lender */

        $this->updateLenderColumns($request, $lender);

        $this->updateLenderDataColumns($request, $lender);

        return $this->getResponse(['message' => 'Lender updated!'], 200);
    }

    protected function getResponse($array, $code): JsonResponse
    {
        return response()->json($array, $code);
    }

    protected function updateLenderColumns(Request $request, Lenders $lender): void
    {
        $fields = [
            'first_loan',
            'min_amount',
            'max_amount',
            'min_term',
            'max_term',
            'receiving_time',
            'min_years',
            'max_years',
            'daily_epc',
        ];

        $update_array = [];
        foreach ($fields as $field) {
            if ($request->{$field}) {
                $update_array[$field] = $request->{$field};
            }
        }

        $update_array = array_merge($update_array, [
            'active' => ($request->active ? $request->active : 0),
            'zero_percent' => ($request->zero_percent ? $request->zero_percent : 0),
            'guaranteed_epc' => ($request->guaranteed_epc ? $request->guaranteed_epc : null),
            'frequency' => ($request->frequency ? $request->frequency : null),
        ]);

        if (!empty($update_array)) {
            $lender->update($update_array);
        }

        if ($request->image) {
            try {
                $image = Image::make($request->image)->encode('png');

                $hash = md5($image->__toString());
                $fileName = "{$hash}.png";
                $relDir = 'storage/logos';
                $fullDir = public_path($relDir);

                if (is_link(public_path('storage')) && !file_exists(public_path('storage'))) {
                    @unlink(public_path('storage'));
                }

                File::ensureDirectoryExists($fullDir, 0755);
                Storage::disk('public')->makeDirectory('logos');

                Storage::disk('public')->put("logos/{$fileName}", (string) $image);

                $fullPath = "{$fullDir}/{$fileName}";
                if (!file_exists($fullPath)) {
                    $image->save($fullPath);
                }

                $lender->update([
                    'image' => "/{$relDir}/{$fileName}",
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to save lender image for {$lender->name}: " . $e->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     * @param Lenders $lender
     * @return void
     */
    protected function updateLenderDataColumns(Request $request, Lenders $lender): void
    {
        $fields_data_local = [
            'slogan',
            'company_name',
            'address',
            'apr_example',
        ];

        $fields_data_common = [
            'phone',
            'email',
            'max_apr',
            'percentage',
        ];

        $lang = $request->slogan_lng;
        $lang2 = $request->slogan2_lng;
        $update_data_lang_array = [];
        foreach ($fields_data_local as $field_data) {
            if ($request->{$field_data}) {
                $update_data_lang_array[$lang][$field_data] = $request->{$field_data};
            }

            if ($request->{$field_data . '2'}) {
                $update_data_lang_array[$lang2][$field_data] = $request->{$field_data . '2'};
            }
        }

        foreach ($fields_data_common as $field_data) {
            if ($request->{$field_data}) {
                $update_data_lang_array[$lang][$field_data] = $request->{$field_data};
                $update_data_lang_array[$lang2][$field_data] = $request->{$field_data};
            }
        }

        if ($lender->dataAPI) {
            if (!empty($update_data_lang_array[$lang])) {
                $lender->dataAPI
                    ->where('lang', $lang)
                    ->where('lender_id', $lender->id)
                    ->update($update_data_lang_array[$lang]);
            }

            if (!empty($update_data_lang_array[$lang2])) {
                $lender->dataAPI
                    ->where('lang', $lang2)
                    ->where('lender_id', $lender->id)
                    ->update($update_data_lang_array[$lang2]);
            }
        }
    }
}
