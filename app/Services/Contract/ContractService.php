<?php

namespace App\Services\Contract;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;




class ContractService
{

    private $contractTypeService;
    private $contractDetailsNewService;
    private $contractDetailsOldService;
    private $contractService;
    private $contractSection;

    public function __construct(
        CustomerContractService $contractService,
        CustomerContractTypeService $contractTypeService,
        CustomerContractDetailsNewService $contractDetailsNewService,
        CustomerContractDetailsOldService $contractDetailsOldService,
        CustomerContractSectionService $contractSection
    ) {
        $this->contractService = $contractService;
        $this->contractTypeService = $contractTypeService;
        $this->contractDetailsNewService = $contractDetailsNewService;
        $this->contractDetailsOldService = $contractDetailsOldService;
        $this->contractSection = $contractSection;
    }


    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            if (isset($data['contract_type_id']) && is_int($data['contract_type_id'])) {
                $contractType = $this->contractTypeService->getById($data['contract_type_id']);
            }

            if (!isset($contractType) || !$contractType) {
                $contractType = $this->contractTypeService->create([
                    'title' => $data['contract_type_title'],
                    'status' => 1
                ]);
            }
            $contract = $this->contractService->create([
                'customer_id' => $data['customer_id'],
                'customer_site_id' => $data['customer_site_id'],
                'contract_type_id' => $contractType->id,
                'created_by_user_id' => $data['created_by_user_id'],
                'before_erp' => $data['before_erp'] ?? false,
                'status' => $data['status'] ?? true,
            ]);

            if ($contract && $contract->before_erp === true && isset($data['file_path'])) {
                $this->contractDetailsOldService->create([
                    'contract_id' => $contract->id,
                    'file_path' => $data['file_path'],
                    'customer_id' => $data['customer_id'],
                    'customer_site_id' => $data['customer_site_id'],
                    'created_by_user_id' => $data['created_by_user_id'],
                    'status' => true,
                ]);
            }
            if ($contract && $contract->before_erp === false && isset($data['sections'])) {
                foreach ($data['sections'] as $section) {
                    $this->contractSection->create([
                        'contract_id' => $contract->id,
                        'title' => $section['title'],
                        'customer_id' => $data['customer_id'],
                        'customer_site_id' => $data['customer_site_id'],
                        'created_by_user_id' => $data['created_by_user_id'],
                        'status' => true,
                    ]);
                }
            }
            DB::commit();
            return $contract;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create contract template: ' . $e->getMessage());
        }
    }
}
