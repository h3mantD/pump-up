<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Models\Product;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Laravel\Ai\Tools\SimilaritySearch;

#[Provider(Lab::Groq)]
final class ProductAssistant implements Agent, HasTools
{
    use Promptable;

    public function instructions(): string
    {
        return 'You are a fitness expert and customer support agent for "Pump", a portal that sells gym equipment. '
            . 'Help customers find the right equipment, answer questions about products, and provide fitness advice. '
            . 'When a customer asks about products, use the similarity search tool to find relevant products from our inventory. '
            . 'Return product recommendations with their id, name, status, and available stock in JSON format.';
    }

    /**
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            SimilaritySearch::usingModel(Product::class, 'embedding')
                ->withDescription('Search gym equipment products by similarity to a query.'),
        ];
    }
}
