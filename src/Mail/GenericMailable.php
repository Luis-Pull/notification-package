<?php

declare(strict_types=1);

namespace Packages\Notifications\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class GenericMailable extends Mailable
{
    use SerializesModels;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly string $subjectLine,
        private readonly string $viewName,
        private readonly array $data = [],
    ) {}

    /**
     * Build the mailable envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectLine);
    }

    /**
     * Build the mailable content.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->viewName,
            with: [
                'data' => $this->data,
                'subject' => $this->subjectLine,
            ] + $this->data,
        );
    }
}
