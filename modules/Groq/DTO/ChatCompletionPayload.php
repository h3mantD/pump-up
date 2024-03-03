<?php

declare(strict_types=1);

namespace Modules\Groq\DTO;

use Modules\Groq\Enums\AiModel;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
final class ChatCompletionPayload extends Data
{
    public function __construct(
        /**
         * @var array<MessagePayload>
         */
        public readonly array $messages,
        public readonly null|int|float $temperature,
        public readonly ?int $maxTokens,
        public readonly ?int $topP,
        public readonly ?bool $stream,
        public readonly AiModel $model = AiModel::MIXTRAL_8X7B,
        public readonly ?string $stop = null,
    ) {
    }

    /**
     * @param  array<MessagePayload>  $messages
     */
    public function setMessages(array $messages): self
    {
        return new self(
            messages: $messages,
            model: $this->model,
            temperature: $this->temperature,
            maxTokens: $this->maxTokens,
            topP: $this->topP,
            stream: $this->stream,
            stop: $this->stop
        );
    }

    public function addMessage(MessagePayload $message): self
    {
        return new self(
            messages: [...$this->messages, $message],
            model: $this->model,
            temperature: $this->temperature,
            maxTokens: $this->maxTokens,
            topP: $this->topP,
            stream: $this->stream,
            stop: $this->stop
        );
    }
}
