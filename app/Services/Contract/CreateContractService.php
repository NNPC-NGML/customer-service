<?php

namespace App\Services\Contract;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Service class responsible for creating customer contracts and their associated templates.
 */
class CreateContractService
{
    /**
     * @var CustomerContractService
     */
    private $contractService;

    /**
     * @var CustomerContractTypeService
     */
    private $contractTypeService;

    /**
     * @var CustomerContractDetailsNewService
     */
    private $contractDetailsNewService;

    /**
     * @var CustomerContractDetailsOldService
     */
    private $contractDetailsOldService;

    /**
     * @var CustomerContractTemplateService
     */
    private $contractTemplate;

    /**
     * CreateContractService constructor.
     *
     * @param CustomerContractService $contractService
     * @param CustomerContractTypeService $contractTypeService
     * @param CustomerContractDetailsNewService $contractDetailsNewService
     * @param CustomerContractDetailsOldService $contractDetailsOldService
     * @param CustomerContractTemplateService $contractTemplate
     */
    public function __construct(
        CustomerContractService $contractService,
        CustomerContractTypeService $contractTypeService,
        CustomerContractDetailsNewService $contractDetailsNewService,
        CustomerContractDetailsOldService $contractDetailsOldService,
        CustomerContractTemplateService $contractTemplate
    ) {
        $this->contractService = $contractService;
        $this->contractTypeService = $contractTypeService;
        $this->contractDetailsNewService = $contractDetailsNewService;
        $this->contractDetailsOldService = $contractDetailsOldService;
        $this->contractTemplate = $contractTemplate;
    }

    /**
     * Creates a contract template along with its associated contract and contract details.
     *
     * This method handles the creation of a contract template, a customer contract, and
     * associated details, both new and old. It handles transactions and ensures that all
     * related data is created consistently.
     *
     * @param array $data The data required to create the contract template and related entities.
     * 
     * @return array An array containing the created contract and template.
     * 
     * @throws ValidationException If validation fails for any of the entities being created.
     * @throws \Exception If any other error occurs during the creation process.
     */
    public function createContractTemplate(array $data)
    {
        DB::beginTransaction();

        try {
            // Retrieve or create contract type
            if (isset($data['contract_type_id']) && is_int($data['contract_type_id'])) {
                $contractType = $this->contractTypeService->getById($data['contract_type_id']);
            }

            if (!isset($contractType) || !$contractType) {
                $contractType = $this->contractTypeService->create([
                    'title' => $data['contract_type_title'],
                    'status' => 1
                ]);
            }

            // Create contract template
            $template = $this->contractTemplate->create([
                'title' => $data['template_title'],
                'status' => 1,
                'created_by_user_id' => $data['created_by_user_id'],
            ]);

            // Create customer contract
            $contract = $this->contractService->create([
                'customer_id' => $data['customer_id'],
                'customer_site_id' => $data['customer_site_id'],
                'contract_type_id' => $contractType->id,
                'created_by_user_id' => $data['created_by_user_id'],
                'before_erp' => $data['before_erp'] ?? false,
                'status' => $data['status'] ?? true,
            ]);

            // Create old contract details if applicable
            if ($data['before_erp'] === true && isset($data['file_path'])) {
                $this->contractDetailsOldService->create([
                    'contract_id' => $contract->id,
                    'file_path' => $data['file_path'],
                    'customer_id' => $data['customer_id'],
                    'customer_site_id' => $data['customer_site_id'],
                    'created_by_user_id' => $data['created_by_user_id'],
                    'status' => true,
                ]);
            }

            // Create new contract details
            foreach ($data['sections'] as $section) {
                $this->contractDetailsNewService->create([
                    'contract_id' => $contract->id,
                    'section_id' => $section['id'],
                    'details' => $section['details'],
                    'customer_id' => $data['customer_id'],
                    'customer_site_id' => $data['customer_site_id'],
                    'created_by_user_id' => $data['created_by_user_id'],
                    'status' => true,
                ]);
            }

            DB::commit();

            return [$contract, $template];
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create contract template: ' . $e->getMessage());
        }
    }
}
