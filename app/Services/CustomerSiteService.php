<?php

namespace App\Services;

use App\Models\CustomerSite;
use Illuminate\Support\Facades\Log;
use App\Jobs\Customer\CustomerCreated;
use App\Jobs\Customer\CustomerSiteCreated;
use App\Jobs\Customer\FormBuilderNotification;

class CustomerSiteService
{



    public function create($data): CustomerSite
    {
        Log::info('Starting customerSite creation process', ['data' => $data]);

        try {
            $arrayData = json_decode($data['form_field_answers'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON in form_field_answers');
            }
            $structuredData = [];

            foreach ($arrayData as $item) {
                $structuredData[$item['key']]  = $item['value'];
            }

            $structuredData['task_id'] = $data['id'];


            $customerSiteCreated = CustomerSite::create($structuredData);

            $formBuilderNotifier['entity'] = 'Customer';
            $formBuilderNotifier['entity_id'] = $customerSiteCreated->customer_id;
            $formBuilderNotifier['entity_site_id'] = $customerSiteCreated->id;
            $formBuilderNotifier['task_id'] = $customerSiteCreated->task_id;


            $customerSiteQueue = config("nnpcreusable.CUSTOMER_SITE_CREATED");
            if (is_array($customerSiteQueue) && !empty($customerSiteQueue)) {
                foreach ($customerSiteQueue as $queue) {
                    $queue = trim($queue);
                    if (!empty($queue)) {
                        Log::info("Dispatching Customer event to queue: " . $queue);
                        CustomerSiteCreated::dispatch($customerSiteQueue)->onQueue($queue);
                        Log::info("After Dispatching Customer event to queue: " . $queue);
                    }
                }
            } else {
                CustomerSiteCreated::dispatch($customerSiteCreated)->onQueue('formbuilder_queue');
            }

            FormBuilderNotification::dispatch($formBuilderNotifier)->onQueue('formbuilder_queue');

            return $customerSiteCreated;
        } catch (\Exception $e) {
            Log::error('Unexpected error during customer site creation: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
