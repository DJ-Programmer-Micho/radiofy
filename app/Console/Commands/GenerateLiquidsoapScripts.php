<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use Illuminate\Support\Facades\File;

class GenerateLiquidsoapScripts extends Command
{
    protected $signature = 'liquidsoap:generate';
    protected $description = 'Generate Liquidsoap scripts for each streaming plan';

    public function handle()
    {
        // Path to your base template
        $templatePath = resource_path('liquidsoap/template.liq');

        if (!File::exists($templatePath)) {
            $this->error("Template file does not exist at $templatePath");
            return 1;
        }

        $templateContent = File::get($templatePath);
        $plans = Plan::all();

        if ($plans->isEmpty()) {
            $this->error("No plans found. Please seed the plans table first.");
            return 1;
        }

        // Directory to store generated Liquidsoap scripts
        $outputDir = resource_path('liquidsoap/generated');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Define encoder details (adjust these as needed)
        $encoder_ip = '192.168.0.113';
        $encoder_port = '8000';

        foreach ($plans as $plan) {
            // Define dynamic values per plan
            // Here, we use a mount name based on bitrate and listener limit.
            $mount = 'plan_' . $plan->bitrate . '_' . $plan->max_listeners;
            $bitrate = $plan->bitrate;  // This is the target output bitrate

            $content = str_replace(
                ['{encoder_ip}', '{encoder_port}', '{mount}', '{bitrate}'],
                [$encoder_ip, $encoder_port, $mount, $bitrate],
                $templateContent
            );

            // Output file: e.g., transcode_64_10.liq
            $fileName = "transcode_{$plan->bitrate}_{$plan->max_listeners}.liq";
            $outputPath = $outputDir . '/' . $fileName;

            File::put($outputPath, $content);
            $this->info("Generated script: $outputPath");
        }

        $this->info("All Liquidsoap scripts generated successfully.");
        return 0;
    }
}
