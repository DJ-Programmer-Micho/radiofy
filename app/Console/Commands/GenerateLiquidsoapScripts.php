<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use Illuminate\Support\Facades\File;

class GenerateLiquidsoapScripts extends Command
{
    protected $signature = 'liquidsoap:generate';
    protected $description = 'Generate Liquidsoap scripts for each unique bitrate plan';

    public function handle()
    {
        // Path to your base template
        $templatePath = resource_path('liquidsoap/template.liq');

        if (!File::exists($templatePath)) {
            $this->error("Template file does not exist at $templatePath");
            return 1;
        }

        $templateContent = File::get($templatePath);

        // Get all plans (even if there are 20 entries)
        $plans = Plan::all();

        if ($plans->isEmpty()) {
            $this->error("No plans found. Please seed the plans table first.");
            return 1;
        }

        // Group by unique bitrate values
        $uniqueBitrates = $plans->pluck('bitrate')->unique();

        // Directory to store generated Liquidsoap scripts
        $outputDir = resource_path('liquidsoap/generated');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Define encoder details (adjust these as needed)
        $encoder_ip = '192.168.0.113';
        $encoder_port = '8000';

        // Loop over each unique bitrate and generate one script per bitrate
        foreach ($uniqueBitrates as $bitrate) {
            // Define dynamic values for this bitrate plan
            $mount = 'plan_' . $bitrate;
            // Replace placeholders in the template:
            $content = str_replace(
                ['{encoder_ip}', '{encoder_port}', '{mount}', '{bitrate}'],
                [$encoder_ip, $encoder_port, $mount, $bitrate],
                $templateContent
            );

            // Output file: e.g., transcode_64.liq
            $fileName = "transcode_{$bitrate}.liq";
            $outputPath = $outputDir . '/' . $fileName;

            File::put($outputPath, $content);
            $this->info("Generated script: $outputPath");
        }

        $this->info("All Liquidsoap scripts generated successfully.");
        return 0;
    }
}
