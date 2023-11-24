<?php

namespace Spatie\DiscordAlerts\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendToDiscordChannelJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    public function __construct(
        public string $text,
        public string $webhookUrl,
        public array|null $embeds = null,
        public string|null $username = null,
        public string|null $avatarUrl = null
    ) {
    }

    public function handle(): void
    {
        $payload = [
            'content' => $this->text
        ];

        if($this->username != null) { $payload['username'] = $this->username; }
        if($this->avatarUrl != null) { $payload['avatar_url'] = $this->avatarUrl; }

        if (! blank($this->embeds)) {
            $payload['embeds'] = $this->embeds;
        }

        Http::post($this->webhookUrl, $payload);
    }
}
