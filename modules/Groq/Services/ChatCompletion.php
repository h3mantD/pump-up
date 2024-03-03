<?php

declare(strict_types=1);

namespace Modules\Groq\Services;

use App\Enums\Method;
use App\Models\Product;
use Illuminate\Support\Arr;
use Modules\Groq\DTO\ChatCompletionPayload;
use Modules\Groq\DTO\MessagePayload;
use Modules\Groq\Enums\Role;

final class ChatCompletion
{
    public function __construct(public Groq $groq)
    {
    }

    public function complete(ChatCompletionPayload $chatCompletionPayload, string $chatRole): array
    {
        // getting the last messages..
        $messages = $chatCompletionPayload->messages;
        $lastMessage = Arr::last($messages)->content;

        // if user is searching a product
        if ('search' === $chatRole) {
            /**
             * @var \Illuminate\Database\Eloquent\Collection<Product> $products
             */
            $products = Product::queryChromaCollection($lastMessage, 30)->get();

            $productsJson = $products->toJson();

            $customMessage = [
                'role' => Role::SYSTEM->value,
                'content' => 'You are fitness expert ai which filters the gym products and you return the response in json format only, here is the list of products that are in the inventory : ' . $productsJson,
            ];

            $lastMessage = [
                'role' => Role::USER->value,
                'content' => 'You are a fitness expert, analyze the query delimited by . And Suggest appropriate products and their link. '
                . "The result should be json formatted with keys 'id', 'name', 'status' and 'available stock'. Dont add new line character in json string " . "You only return the json object of products don't show any extra details for information if there are no productes to be found then return the empty array \n"
                . "``` {$lastMessage} ```",
            ];
        } else {
            // else act as a normal chatbot
            $customMessage = [
                'role' => Role::SYSTEM->value,
                'content' => 'You are a fitness expert',
            ];

            $lastMessage = [
                'role' => Role::USER->value,
                'content' => $lastMessage,
            ];
        }

        $chatCompletionPayload = $chatCompletionPayload->addMessage(MessagePayload::from($customMessage));
        $chatCompletionPayload = $chatCompletionPayload->addMessage(MessagePayload::from($lastMessage));

        $response = $this->groq->send(
            method: Method::POST,
            url: 'chat/completions',
            body: $chatCompletionPayload->toArray()
        );

        return $response->json();
    }
}
