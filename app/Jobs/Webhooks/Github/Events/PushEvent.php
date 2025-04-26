<?php

namespace App\Jobs\Webhooks\Github\Events;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PushEvent extends ProcessWebhookJob
{
    const STORAGE_PATH = __DIR__.'/../../../../../storage/';

    const DEPLOY_FILE_DEVELOP = self::STORAGE_PATH.'/app/private/deployments/develop/deploy.sh';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->webhookCall->payload;

        $branch = $data['ref'] ?? '';

        $response = match ($branch) {
            'refs/heads/develop' => $this->handleDevelopBranch(),
            default => null,
        };

        echo '<pre>';
        print_r($response);
        echo '</pre>';
    }

    private function handleDevelopBranch(): string
    {
        $process = new Process(['bash', self::DEPLOY_FILE_DEVELOP]);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
