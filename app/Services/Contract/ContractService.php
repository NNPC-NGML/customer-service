<?php

namespace App\Services\Contract;

use Illuminate\Support\Facades\DB;
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

    // public function update(int $id, array $data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $contract = $this->contractService->getById($id);
    //         if (!$contract) {
    //             throw new \Exception("Contract not found");
    //         }
    //         if (isset($data['contract_type_id'])) {
    //             $contractType = $this->contractTypeService->getById($data['contract_type_id']);
    //             if (!$contractType) {
    //                 throw new \Exception("Contract type not found");
    //             }
    //         }

    //         $this->contractService->update($id, $data);

    //         if ($contract && $contract->before_erp === true && isset($data['file_path'])) {
    //             $this->contractDetailsOldService->update($data['file_path_id'], [
    //                 'contract_id' => $contract->id,
    //                 'file_path' => $data['file_path'],
    //                 'customer_id' => $data['customer_id'],
    //                 'customer_site_id' => $data['customer_site_id'],
    //                 'created_by_user_id' => $data['created_by_user_id'],
    //                 'status' => $data['status'],
    //             ]);
    //         }

    //         if ($contract && $contract->before_erp === true &&  isset($data['sections'])) {
    //             foreach ($data['sections'] as $section) {
    //                 $this->contractSection->update($section->id, [
    //                     'contract_id' => $contract->id,
    //                     'title' => $section['title'],
    //                     'customer_id' => $contract->customer_id,
    //                     'customer_site_id' => $contract->customer_site_id,
    //                     'created_by_user_id' => $contract->created_by_user_id,
    //                     'status' => $section['status'],
    //                 ]);
    //             }
    //         }
    //         DB::commit();
    //         return $contract;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw new \Exception('Failed to update contract: ' . $e->getMessage());
    //     }
    // }

    // public function update(int $id, array $data)
    // {
    //     DB::beginTransaction();
    //     try {

    //         $contract = $this->contractService->getById($id);
    //         if (!$contract) {
    //             throw new \Exception("Contract not found");
    //         }


    //         if (isset($data['contract_type_id'])) {
    //             $contractType = $this->contractTypeService->getById($data['contract_type_id']);
    //             if (!$contractType) {
    //                 throw new \Exception("Contract type not found");
    //             }
    //             $data['contract_type_id'] = $contractType->id;
    //         }


    //         $this->contractService->update($id, $data);

    //         // Update contract details old (for before_erp contracts)
    //         if ($contract->before_erp === true && isset($data['file_path'])) {
    //             if (!isset($data['file_path_id'])) {
    //                 throw new \Exception('File path ID is required for updating old contract details.');
    //             }
    //             $this->contractDetailsOldService->update($data['file_path_id'], [
    //                 'contract_id' => $contract->id,
    //                 'file_path' => $data['file_path'],
    //                 'customer_id' => $data['customer_id'],
    //                 'customer_site_id' => $data['customer_site_id'],
    //                 'created_by_user_id' => $data['created_by_user_id'],
    //                 'status' => $data['status'] ?? true,
    //             ]);
    //         }

    //         // Update contract sections (for non-before_erp contracts)
    //         if ($contract->before_erp === false && isset($data['sections'])) {
    //             foreach ($data['sections'] as $section) {
    //                 if (isset($section['id'])) {
    //                     $this->contractSection->update($section['id'], [
    //                         'contract_id' => $contract->id,
    //                         'title' => $section['title'],
    //                         'customer_id' => $data['customer_id'],
    //                         'customer_site_id' => $data['customer_site_id'],
    //                         'created_by_user_id' => $data['created_by_user_id'],
    //                         'status' => $section['status'] ?? true,
    //                     ]);
    //                 } else {
    //                     // Create new sections if 'id' is not set
    //                     $this->contractSection->create([
    //                         'contract_id' => $contract->id,
    //                         'title' => $section['title'],
    //                         'customer_id' => $data['customer_id'],
    //                         'customer_site_id' => $data['customer_site_id'],
    //                         'created_by_user_id' => $data['created_by_user_id'],
    //                         'status' => $section['status'] ?? true,
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         return $contract;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw new \Exception('Failed to update contract: ' . $e->getMessage());
    //     }
    // }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            // Retrieve the contract
            $contract = $this->contractService->getById($id);
            if (!$contract) {
                throw new \Exception("Contract not found");
            }

            // Handle contract type if provided
            if (isset($data['contract_type_id'])) {
                $contractType = $this->contractTypeService->getById($data['contract_type_id']);
                if (!$contractType) {
                    throw new \Exception("Contract type not found");
                }
                $data['contract_type_id'] = $contractType->id;
            }

            // Update the contract
            $contract = $this->contractService->update($id, $data);

            // Update contract details old (for before_erp contracts)
            if ($contract->before_erp === true && isset($data['file_path'])) {
                if (!isset($data['file_path_id'])) {
                    throw new \Exception('File path ID is required for updating old contract details.');
                }
                $detailOld = $this->contractDetailsOldService->getById($data['file_path_id']);
                if ($detailOld) {
                    $this->contractDetailsOldService->update($detailOld, [
                        'contract_id' => $contract->id,
                        'file_path' => $data['file_path'],
                        'customer_id' => $data['customer_id'],
                        'customer_site_id' => $data['customer_site_id'],
                        'created_by_user_id' => $data['created_by_user_id'],
                        'status' => $data['status'] ?? true,
                    ]);
                }
            }

            // Update or create contract sections (for non-before_erp contracts)
            if ($contract->before_erp === false && isset($data['sections'])) {
                foreach ($data['sections'] as $section) {
                    if (isset($section['id'])) {
                        $existingSection = $this->contractSection->getById($section['id']);
                        if ($existingSection) {
                            $this->contractSection->update($existingSection->id, [
                                'contract_id' => $contract->id,
                                'title' => $section['title'],
                                'customer_id' => $data['customer_id'],
                                'customer_site_id' => $data['customer_site_id'],
                                'created_by_user_id' => $data['created_by_user_id'],
                                'status' => $section['status'] ?? true,
                            ]);
                        }
                    } else {
                        // Create new section if 'id' is not set
                        $this->contractSection->create([
                            'contract_id' => $contract->id,
                            'title' => $section['title'],
                            'customer_id' => $data['customer_id'],
                            'customer_site_id' => $data['customer_site_id'],
                            'created_by_user_id' => $data['created_by_user_id'],
                            'status' => $section['status'] ?? true,
                        ]);
                    }
                }
            }

            DB::commit();
            return $contract;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update contract: ' . $e->getMessage());
        }
    }
}
